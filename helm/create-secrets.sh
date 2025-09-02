#!/bin/bash
# Script to create all required secrets for KairoFlow deployment

NAMESPACE="kairoflow"

echo "Creating secrets in namespace: $NAMESPACE"

# Database secret
echo "Creating database secret..."
kubectl create secret generic kairoflow-database-secret \
  --namespace=$NAMESPACE \
  --from-literal=host=postgres.local \
  --from-literal=port=5432 \
  --from-literal=name=kairoflow \
  --from-literal=user=kairoflow_user \
  --from-literal=password=changeme \
  --dry-run=client -o yaml | kubectl apply -f -

# Redis secret
echo "Creating Redis secret..."
kubectl create secret generic kairoflow-redis-secret \
  --namespace=$NAMESPACE \
  --from-literal=host=redis.local \
  --from-literal=port=6379 \
  --from-literal=password="" \
  --dry-run=client -o yaml | kubectl apply -f -

# MinIO secret
echo "Creating MinIO secret..."
kubectl create secret generic kairoflow-minio-secret \
  --namespace=$NAMESPACE \
  --from-literal=endpoint=minio.local \
  --from-literal=accessKey=minioadmin \
  --from-literal=secretKey=minioadmin \
  --dry-run=client -o yaml | kubectl apply -f -

# OpenAI secret
echo "Creating OpenAI secret..."
kubectl create secret generic kairoflow-openai-secret \
  --namespace=$NAMESPACE \
  --from-literal=apiKey=sk-placeholder \
  --dry-run=client -o yaml | kubectl apply -f -

# App secrets (if not exists)
echo "Creating app secrets..."
kubectl create secret generic kairoflow-app-secret \
  --namespace=$NAMESPACE \
  --from-literal=appSecret=$(openssl rand -hex 32) \
  --from-literal=jwtSecret=$(openssl rand -hex 32) \
  --dry-run=client -o yaml | kubectl apply -f -

echo "All secrets created/updated!"
echo ""
echo "Checking secrets:"
kubectl get secrets -n $NAMESPACE