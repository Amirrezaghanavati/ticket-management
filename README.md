# Clone the repository
git clone https://github.com/Amirrezaghanavati/ticket-management.git
cd ticket-management

# Install PHP dependencies
composer install

# Copy and configure environment file
cp .env.example .env
# Update database credentials and other environment variables in .env

# Generate application key
php artisan key:generate

# Run database migrations and seed default data
php artisan migrate --seed

# (Optional) Build front-end assets
npm install
npm run build   # or npm run dev

# Start local development server
php artisan serve
