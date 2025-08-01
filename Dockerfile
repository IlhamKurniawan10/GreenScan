FROM php:8.2-cli

# Install curl untuk akses OpenRouter
RUN apt-get update && apt-get install -y curl

# Copy semua file ke dalam container
WORKDIR /var/www
COPY . .

# Jalankan built-in server di port 8080
EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080"]
