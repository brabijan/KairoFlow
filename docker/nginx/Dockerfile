FROM nginx:alpine

# Remove default config
RUN rm /etc/nginx/conf.d/default.conf

# Copy custom configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy static files
COPY --chown=nginx:nginx public /var/www/html/www

# Create required directories
RUN mkdir -p /var/www/html/www/uploads \
    && chown -R nginx:nginx /var/www/html/www \
    && chmod -R 755 /var/www/html/www

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Expose port
EXPOSE 80

# Start nginx
CMD ["nginx", "-g", "daemon off;"]