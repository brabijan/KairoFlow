#!/bin/bash
# Script to deploy/upgrade KairoFlow using Helm locally

NAMESPACE="kairoflow"
RELEASE_NAME="kairoflow"

echo "Deploying KairoFlow to namespace: $NAMESPACE"

# First, ensure namespace exists
echo "Creating namespace..."
kubectl create namespace $NAMESPACE --dry-run=client -o yaml | kubectl apply -f -

# Deploy with Helm (this will create all resources including secrets)
echo "Deploying with Helm..."
helm upgrade --install $RELEASE_NAME ./helm/kairoflow \
  --namespace $NAMESPACE \
  --create-namespace \
  --wait \
  --timeout 5m

echo ""
echo "Deployment status:"
kubectl get all -n $NAMESPACE

echo ""
echo "Secrets created:"
kubectl get secrets -n $NAMESPACE

echo ""
echo "Pod status:"
kubectl get pods -n $NAMESPACE

echo ""
echo "To check pod logs:"
echo "kubectl logs -n $NAMESPACE <pod-name> -c php"
echo "kubectl logs -n $NAMESPACE <pod-name> -c nginx"

echo ""
echo "To describe failing pod:"
echo "kubectl describe pod -n $NAMESPACE <pod-name>"

echo ""
echo "To uninstall:"
echo "helm uninstall $RELEASE_NAME -n $NAMESPACE"