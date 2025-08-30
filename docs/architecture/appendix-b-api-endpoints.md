# Appendix B: API Endpoints

| Endpoint | Method | Purpose | Auth Required |
|----------|--------|---------|---------------|
| `/health` | GET | Liveness probe | No |
| `/ready` | GET | Readiness probe | No |
| `/login` | POST | User authentication | No |
| `/logout` | POST | End session | Yes |
| `/dashboard` | GET | Main dashboard | Yes |
| `/payments` | GET | Payment list | Yes |
| `/payments/{id}/qr` | GET | Generate QR code | Yes |
| `/payments/{id}/complete` | POST | Mark as paid | Yes |
| `/tasks` | GET | Task inbox | Yes |
| `/tasks/{id}/sync` | POST | Create GitLab issue | Yes |
| `/api/webhook/gitlab` | POST | GitLab webhooks | Token |
| `/api/webhook/slack` | POST | Slack events | Token |

---

This technical architecture document provides a comprehensive blueprint for building KairoFlow with emphasis on the email processing pipeline, Kubernetes deployment, and API integration patterns as requested. The architecture is specifically optimized for ADHD users while maintaining technical excellence and operational reliability.