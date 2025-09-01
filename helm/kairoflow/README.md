# KairoFlow Helm Chart

This Helm chart deploys KairoFlow - an ADHD-optimized financial & task management system - to Kubernetes.

## Prerequisites

- Kubernetes 1.28+
- Helm 3.x
- cert-manager (for TLS certificates)
- Traefik ingress controller
- External PostgreSQL database
- External Redis instance
- S3-compatible storage (MinIO or AWS S3)
- GitHub Container Registry access

## Installation

### 1. Create Namespace

```bash
kubectl create namespace kairoflow
```

### 2. Create Secrets

#### GitHub Container Registry Secret

Create a GitHub Personal Access Token with `read:packages` scope, then:

```bash
kubectl create secret docker-registry ghcr-secret \
  --namespace=kairoflow \
  --docker-server=ghcr.io \
  --docker-username=YOUR_GITHUB_USERNAME \
  --docker-password=YOUR_GITHUB_PAT
```

#### Database Secret (if not using values)

```bash
kubectl create secret generic kairoflow-database-secret \
  --namespace=kairoflow \
  --from-literal=host=your-postgres-host \
  --from-literal=port=5432 \
  --from-literal=name=kairoflow \
  --from-literal=user=kairoflow_user \
  --from-literal=password=your-secure-password
```

#### Redis Secret (if not using values)

```bash
kubectl create secret generic kairoflow-redis-secret \
  --namespace=kairoflow \
  --from-literal=host=your-redis-host \
  --from-literal=port=6379 \
  --from-literal=password=your-redis-password  # Optional
```

#### MinIO/S3 Secret (if not using values)

```bash
kubectl create secret generic kairoflow-minio-secret \
  --namespace=kairoflow \
  --from-literal=endpoint=s3.amazonaws.com \
  --from-literal=accessKey=your-access-key \
  --from-literal=secretKey=your-secret-key
```

#### OpenAI Secret (if not using values)

```bash
kubectl create secret generic kairoflow-openai-secret \
  --namespace=kairoflow \
  --from-literal=apiKey=your-openai-api-key
```

#### Application Secrets (if not using values)

```bash
kubectl create secret generic kairoflow-app-secret \
  --namespace=kairoflow \
  --from-literal=appSecret=$(openssl rand -hex 32) \
  --from-literal=jwtSecret=$(openssl rand -hex 32)
```

### 3. Configure Values

Create a custom values file `values.custom.yaml`:

```yaml
# Image configuration
image:
  nginx:
    repository: ghcr.io/your-org/kairoflow/nginx
    tag: "latest"
  php:
    repository: ghcr.io/your-org/kairoflow/php
    tag: "latest"

# Ingress configuration
ingress:
  host: kairoflow.yourdomain.com

# External database
database:
  host: postgres.example.com
  user: kairoflow
  password: "your-secure-password"

# External Redis
redis:
  host: redis.example.com
  password: ""  # Leave empty if no auth

# MinIO/S3
minio:
  endpoint: s3.amazonaws.com
  accessKey: "your-access-key"
  secretKey: "your-secret-key"

# OpenAI
openai:
  apiKey: "your-openai-api-key"

# Application secrets
appSecrets:
  appSecret: "generate-with-openssl-rand-hex-32"
  jwtSecret: "generate-with-openssl-rand-hex-32"

# GHCR authentication
ghcr:
  username: "your-github-username"
  password: "your-github-pat"
```

### 4. Install the Chart

```bash
# Install with custom values
helm install kairoflow ./helm/kairoflow \
  --namespace kairoflow \
  --values values.custom.yaml

# Or upgrade existing installation
helm upgrade kairoflow ./helm/kairoflow \
  --namespace kairoflow \
  --values values.custom.yaml
```

## Configuration

### Key Configuration Options

| Parameter | Description | Default |
|-----------|-------------|---------|
| `replicaCount` | Number of application replicas | `1` |
| `image.nginx.repository` | Nginx image repository | `ghcr.io/your-org/kairoflow/nginx` |
| `image.php.repository` | PHP image repository | `ghcr.io/your-org/kairoflow/php` |
| `ingress.host` | Application hostname | `kairoflow.example.com` |
| `ingress.tls.enabled` | Enable HTTPS | `true` |
| `database.host` | PostgreSQL host | `""` |
| `redis.host` | Redis host | `""` |
| `persistence.uploads.size` | Upload storage size | `5Gi` |
| `cronjobs.emailProcessor.enabled` | Enable email processing | `true` |

### Environment-Specific Values

For different environments, create separate values files:

```bash
# Development
helm install kairoflow ./helm/kairoflow -f values.dev.yaml

# Production
helm install kairoflow ./helm/kairoflow -f values.prod.yaml
```

## Upgrading

To upgrade the application:

```bash
# Upgrade with new image tag
helm upgrade kairoflow ./helm/kairoflow \
  --namespace kairoflow \
  --set image.php.tag=v1.2.0 \
  --set image.nginx.tag=v1.2.0
```

## Rollback

To rollback to a previous release:

```bash
# List releases
helm list --namespace kairoflow

# Show history
helm history kairoflow --namespace kairoflow

# Rollback to previous version
helm rollback kairoflow --namespace kairoflow

# Rollback to specific version
helm rollback kairoflow 3 --namespace kairoflow
```

## Uninstallation

To remove the application:

```bash
# Uninstall the release
helm uninstall kairoflow --namespace kairoflow

# Delete persistent volumes (WARNING: This deletes data!)
kubectl delete pvc -n kairoflow --all

# Delete namespace
kubectl delete namespace kairoflow
```

## Troubleshooting

### Check Pod Status

```bash
kubectl get pods -n kairoflow
kubectl describe pod <pod-name> -n kairoflow
kubectl logs <pod-name> -n kairoflow -c nginx
kubectl logs <pod-name> -n kairoflow -c php
```

### Check Ingress

```bash
kubectl get ingressroute -n kairoflow
kubectl describe ingressroute kairoflow-https -n kairoflow
```

### Check Secrets

```bash
kubectl get secrets -n kairoflow
kubectl describe secret kairoflow-database-secret -n kairoflow
```

### Common Issues

#### Pods not starting
- Check image pull secrets: `kubectl get events -n kairoflow`
- Verify GHCR authentication
- Check resource limits

#### Database connection errors
- Verify database credentials in secret
- Check network connectivity to external database
- Ensure database exists and user has permissions

#### Storage issues
- Check PVC status: `kubectl get pvc -n kairoflow`
- Verify storage class exists: `kubectl get storageclass`
- Check available storage capacity

#### CronJob not running
- Check CronJob status: `kubectl get cronjob -n kairoflow`
- View job history: `kubectl get jobs -n kairoflow`
- Check job logs: `kubectl logs job/<job-name> -n kairoflow`

## Security Considerations

1. **Secrets Management**
   - Use external secret management (e.g., Sealed Secrets, External Secrets Operator)
   - Rotate credentials regularly
   - Never commit secrets to version control

2. **Network Security**
   - Use NetworkPolicies to restrict pod communication
   - Enable HTTPS only (redirect HTTP to HTTPS)
   - Configure proper CORS headers

3. **Image Security**
   - Scan images for vulnerabilities
   - Use specific tags, not `latest`
   - Sign images with cosign

4. **RBAC**
   - Use minimal ServiceAccount permissions
   - Implement proper role bindings
   - Audit access regularly

## Monitoring

### Prometheus Metrics

The application exposes metrics at `/metrics` endpoint. Configure Prometheus ServiceMonitor:

```yaml
apiVersion: monitoring.coreos.com/v1
kind: ServiceMonitor
metadata:
  name: kairoflow
  namespace: kairoflow
spec:
  selector:
    matchLabels:
      app.kubernetes.io/name: kairoflow
  endpoints:
    - port: http
      path: /metrics
```

### Health Checks

- Liveness: `http://<service>/health`
- Readiness: `http://<service>/health`

## Support

For issues and questions:
- GitHub Issues: https://github.com/your-org/kairoflow/issues
- Documentation: https://docs.kairoflow.example.com

## License

See LICENSE file in the repository root.