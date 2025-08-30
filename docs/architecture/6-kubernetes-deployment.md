# 6. Kubernetes Deployment

## Deployment Architecture

```yaml
apiVersion: v1
kind: Namespace
metadata:
  name: kairoflow
---
apiVersion: apps/v1
kind: Deployment
metadata:
  name: kairoflow-app
  namespace: kairoflow
spec:
  replicas: 2
  strategy:
    type: RollingUpdate
    rollingUpdate:
      maxSurge: 1
      maxUnavailable: 0
  selector:
    matchLabels:
      app: kairoflow
  template:
    metadata:
      labels:
        app: kairoflow
    spec:
      containers:
      - name: app
        image: kairoflow:latest
        ports:
        - containerPort: 80
        env:
        - name: DATABASE_URL
          valueFrom:
            secretKeyRef:
              name: kairoflow-secrets
              key: database-url
        - name: REDIS_URL
          valueFrom:
            secretKeyRef:
              name: kairoflow-secrets
              key: redis-url
        resources:
          requests:
            memory: "256Mi"
            cpu: "250m"
          limits:
            memory: "512Mi"
            cpu: "500m"
        livenessProbe:
          httpGet:
            path: /health
            port: 80
          initialDelaySeconds: 30
          periodSeconds: 10
        readinessProbe:
          httpGet:
            path: /ready
            port: 80
          initialDelaySeconds: 5
          periodSeconds: 5
        volumeMounts:
        - name: file-storage
          mountPath: /app/www/uploads
      volumes:
      - name: file-storage
        persistentVolumeClaim:
          claimName: kairoflow-files
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
          containers:
          - name: processor
            image: kairoflow:latest
            command: ["php", "bin/console", "email:process"]
            env:
            - name: DATABASE_URL
              valueFrom:
                secretKeyRef:
                  name: kairoflow-secrets
                  key: database-url
            resources:
              requests:
                memory: "128Mi"
                cpu: "100m"
              limits:
                memory: "256Mi"
                cpu: "200m"
          restartPolicy: OnFailure
```

## Helm Chart Structure

```yaml