# 6. Kubernetes Deployment

## Overview

KairoFlow uses a modern Kubernetes deployment architecture based on proven patterns from production systems. The deployment leverages external managed services for databases and caching, uses GitHub Container Registry for images, and implements CI/CD through GitHub Actions.

## Architecture Principles

### External Services Strategy
- **PostgreSQL**: Use managed database service (AWS RDS, Azure Database, Google Cloud SQL)
- **Redis**: Use managed Redis service (AWS ElastiCache, Azure Cache, Google Memorystore)
- **Object Storage**: Use S3-compatible storage (AWS S3, MinIO, DigitalOcean Spaces)
- **Benefits**: High availability, automated backups, managed updates, no stateful workloads in K8s

### Container Architecture
- **Two-container pod pattern**: Nginx + PHP-FPM in same pod
- **Nginx**: Serves static files, proxies PHP requests to localhost:9000
- **PHP-FPM**: Handles application logic, database connections
- **Shared volumes**: Both containers mount same persistent volumes

## Deployment Configuration

### Web Application Deployment

```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: web-app
  namespace: kairoflow
spec:
  replicas: 1  # Start with 1, scale as needed
  selector:
    matchLabels:
      app: web-app
  template:
    metadata:
      labels:
        app: web-app
    spec:
      serviceAccountName: default
      imagePullSecrets:
        - name: ghcr
      containers:
        # Nginx container
        - name: nginx
          image: ghcr.io/yourusername/kairoflow/nginx:latest
          ports:
            - containerPort: 80
          volumeMounts:
            - name: uploads
              mountPath: /var/www/html/www/uploads
            - name: sessions
              mountPath: /var/www/html/var/tmp/session
            - name: nginx-config
              mountPath: /etc/nginx/conf.d/default.conf
              subPath: default.conf
            - name: nginx-config
              mountPath: /etc/nginx/nginx.conf
              subPath: nginx.conf
        
        # PHP-FPM container
        - name: php
          image: ghcr.io/yourusername/kairoflow/php:latest
          ports:
            - containerPort: 9000
          lifecycle:
            postStart:
              exec:
                command: ["/bin/sh", "-c", "php bin/console migrations:migrate --no-interaction --allow-no-migration || true"]
          env:
            - name: NETTE_ENV
              value: "prod"
            - name: NETTE_DEBUG
              value: "0"
            - name: PHP_MAX_EXECUTION_TIME
              value: "600"
            - name: PHP_MEMORY_LIMIT
              value: "512M"
            - name: DATABASE_HOST
              valueFrom:
                secretKeyRef:
                  name: database-secret
                  key: host
            # ... other environment variables from secrets
          volumeMounts:
            - name: uploads
              mountPath: /var/www/html/www/uploads
            - name: sessions
              mountPath: /var/www/html/var/tmp/session
      volumes:
        - name: uploads
          persistentVolumeClaim:
            claimName: uploads-pvc
        - name: sessions
          persistentVolumeClaim:
            claimName: sessions-pvc
        - name: nginx-config
          configMap:
            name: nginx-config
```

### Nginx Configuration (ConfigMap)

```yaml
apiVersion: v1
kind: ConfigMap
metadata:
  name: nginx-config
  namespace: kairoflow
data:
  default.conf: |
    upstream php-upstream {
        server 127.0.0.1:9000;
    }
    
    server {
        listen 80 default_server;
        server_name localhost;
        root /var/www/html/www/;
        index index.php;
        
        client_max_body_size 500M;
        
        location / {
            try_files $uri $uri/ /index.php$is_args$args;
        }
        
        location ~ \.php$ {
            try_files $uri /index.php =404;
            fastcgi_pass php-upstream;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
            fastcgi_read_timeout 600;
        }
    }
    
  nginx.conf: |
    user nginx;
    worker_processes auto;
    
    events {
        worker_connections 1024;
    }
    
    http {
        include /etc/nginx/mime.types;
        default_type application/octet-stream;
        
        sendfile on;
        keepalive_timeout 65;
        
        client_max_body_size 500M;
        proxy_connect_timeout 600;
        proxy_send_timeout 600;
        proxy_read_timeout 600;
        
        include /etc/nginx/conf.d/*.conf;
    }
```

## Ingress Configuration (Traefik)

```yaml
apiVersion: traefik.io/v1alpha1
kind: IngressRoute
metadata:
  name: kairoflow-https
  namespace: kairoflow
spec:
  entryPoints:
    - websecure
  routes:
    - match: Host(`kairoflow.carpiftw.cz`)
      kind: Rule
      services:
        - name: web-app
          port: 80
  tls:
    secretName: kairoflow-tls

---
apiVersion: traefik.io/v1alpha1
kind: IngressRoute
metadata:
  name: kairoflow-http
  namespace: kairoflow
spec:
  entryPoints:
    - web
  routes:
    - match: Host(`kairoflow.carpiftw.cz`)
      kind: Rule
      services:
        - name: web-app
          port: 80
      middlewares:
        - name: redirect-https

---
apiVersion: traefik.io/v1alpha1
kind: Middleware
metadata:
  name: redirect-https
  namespace: kairoflow
spec:
  redirectScheme:
    scheme: https
    permanent: true
```

## TLS Certificate (cert-manager)

```yaml
apiVersion: cert-manager.io/v1
kind: Certificate
metadata:
  name: kairoflow-tls
  namespace: kairoflow
spec:
  secretName: kairoflow-tls
  issuerRef:
    name: letsencrypt-prod
    kind: ClusterIssuer
  dnsNames:
    - kairoflow.carpiftw.cz
```

## Persistent Storage

```yaml
# Uploads storage
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: uploads-pvc
  namespace: kairoflow
spec:
  accessModes:
    - ReadWriteOnce
  resources:
    requests:
      storage: 5Gi

---
# Sessions storage
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: sessions-pvc
  namespace: kairoflow
spec:
  accessModes:
    - ReadWriteOnce
  resources:
    requests:
      storage: 2Gi
```

## Secrets Management

### Database Secret
```yaml
apiVersion: v1
kind: Secret
metadata:
  name: database-secret
  namespace: kairoflow
type: Opaque
stringData:
  host: "your-db-host.amazonaws.com"
  user: "kairoflow"
  password: "secure-password"
  dbname: "kairoflow"
  port: "5432"
```

### GitHub Container Registry Secret
```yaml
apiVersion: v1
kind: Secret
metadata:
  name: ghcr
  namespace: kairoflow
type: kubernetes.io/dockerconfigjson
data:
  .dockerconfigjson: <base64-encoded-docker-config>
```

### ServiceAccount Configuration
```yaml
apiVersion: v1
kind: ServiceAccount
metadata:
  name: default
  namespace: kairoflow
imagePullSecrets:
  - name: ghcr
```

## CronJob Configuration

```yaml
apiVersion: batch/v1
kind: CronJob
metadata:
  name: email-processor
  namespace: kairoflow
spec:
  schedule: "*/1 * * * *"  # Every minute
  concurrencyPolicy: Forbid  # Prevent overlapping runs
  successfulJobsHistoryLimit: 3
  failedJobsHistoryLimit: 3
  jobTemplate:
    spec:
      activeDeadlineSeconds: 50  # Kill if runs > 50 seconds
      template:
        spec:
          serviceAccountName: default
          imagePullSecrets:
            - name: ghcr
          containers:
          - name: processor
            image: ghcr.io/yourusername/kairoflow/php:latest
            command: ["php", "bin/console", "app:email:process"]
            env:
              # Same environment variables as main deployment
              - name: DATABASE_HOST
                valueFrom:
                  secretKeyRef:
                    name: database-secret
                    key: host
            resources:
              requests:
                memory: "128Mi"
              limits:
                memory: "256Mi"
          restartPolicy: OnFailure
```

## CI/CD with GitHub Actions

### Workflow Overview
1. **Build**: Create Docker images for PHP and Nginx
2. **Push**: Upload images to GitHub Container Registry
3. **Deploy**: Apply Kubernetes manifests to cluster
4. **Rollout**: Restart deployments with new images

### GitHub Actions Workflow
```yaml
name: Build and Deploy

on:
  push:
    branches: [master]

env:
  REGISTRY: ghcr.io
  PHP_IMAGE_NAME: ghcr.io/${{ github.repository }}/php
  NGINX_IMAGE_NAME: ghcr.io/${{ github.repository }}/nginx

jobs:
  build-and-deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Log in to GitHub Container Registry
        uses: docker/login-action@v2
        with:
          registry: ${{ env.REGISTRY }}
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}
      
      - name: Build and push PHP image
        uses: docker/build-push-action@v4
        with:
          context: .
          file: .docker/php/Dockerfile
          push: true
          tags: |
            ${{ env.PHP_IMAGE_NAME }}:latest
            ${{ env.PHP_IMAGE_NAME }}:${{ github.sha }}
      
      - name: Build and push Nginx image
        uses: docker/build-push-action@v4
        with:
          context: .
          file: .docker/nginx/Dockerfile
          push: true
          tags: |
            ${{ env.NGINX_IMAGE_NAME }}:latest
            ${{ env.NGINX_IMAGE_NAME }}:${{ github.sha }}
      
      - name: Deploy to Kubernetes
        run: |
          echo "${{ secrets.KUBECONFIG }}" | base64 -d > kubeconfig
          export KUBECONFIG=kubeconfig
          kubectl apply -f k8s/
          kubectl rollout restart deployment/web-app -n kairoflow
          kubectl rollout status deployment/web-app -n kairoflow
```

## Monitoring and Health Checks

### Liveness Probe
- **Path**: /health (served by Nginx)
- **Initial Delay**: 30 seconds
- **Period**: 10 seconds
- **Failure Threshold**: 3

### Readiness Probe
- **Path**: /health
- **Initial Delay**: 5 seconds
- **Period**: 5 seconds
- **Success Threshold**: 1

## Scaling Strategy

### Horizontal Pod Autoscaling
```yaml
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: web-app-hpa
  namespace: kairoflow
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: web-app
  minReplicas: 1
  maxReplicas: 5
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          averageUtilization: 70
    - type: Resource
      resource:
        name: memory
        target:
          type: Utilization
          averageUtilization: 80
```

## Security Considerations

1. **Secrets Management**:
   - All sensitive data stored in Kubernetes Secrets
   - Never hardcode credentials in manifests or images
   - Use sealed-secrets or external-secrets-operator for GitOps

2. **Network Policies**:
   - Restrict pod-to-pod communication
   - Allow only necessary ingress/egress

3. **Container Security**:
   - Run containers as non-root user (www-data)
   - Use read-only root filesystem where possible
   - Regular security scanning of images

4. **RBAC**:
   - Minimal permissions for ServiceAccounts
   - Separate namespaces for different environments

## Disaster Recovery

1. **Database Backups**:
   - Automated daily backups via managed service
   - Point-in-time recovery capability
   - Cross-region backup replication

2. **Persistent Volume Backups**:
   - Regular snapshots of PVCs
   - Velero for cluster-level backups

3. **Configuration Backups**:
   - All manifests in Git repository
   - GitOps approach for configuration management

## Performance Optimization

1. **Resource Allocation**:
   - PHP: 256Mi-512Mi memory, 250m-500m CPU
   - Nginx: 64Mi-128Mi memory, 50m-100m CPU
   - Adjust based on load testing

2. **Caching Strategy**:
   - Redis for session storage
   - OPcache for PHP bytecode
   - CDN for static assets

3. **Database Optimization**:
   - Connection pooling
   - Prepared statements
   - Query optimization

## Troubleshooting

### Common Issues

1. **Pod not starting**:
   - Check image pull secrets: `kubectl describe pod <pod-name>`
   - Verify secrets are mounted: `kubectl get secret -n kairoflow`

2. **Database connection errors**:
   - Verify database secret values
   - Check network connectivity to external database
   - Review security groups/firewall rules

3. **High memory usage**:
   - Review PHP memory limits
   - Check for memory leaks in application
   - Monitor with `kubectl top pods`

### Useful Commands

```bash
# View pod logs
kubectl logs -n kairoflow deployment/web-app -c php
kubectl logs -n kairoflow deployment/web-app -c nginx

# Execute commands in pod
kubectl exec -n kairoflow deployment/web-app -c php -- php bin/console

# Port forward for debugging
kubectl port-forward -n kairoflow deployment/web-app 8080:80

# Check resource usage
kubectl top pods -n kairoflow

# Describe deployment
kubectl describe deployment web-app -n kairoflow
```