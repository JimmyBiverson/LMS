#!/bin/bash

echo "🚀 LMS System Verification Script"
echo "=================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}✗ Error: Not in LMS directory${NC}"
    echo "Please run this script from the LMS root directory"
    exit 1
fi

echo -e "${YELLOW}📋 Checking system requirements...${NC}"
echo ""

# Check PHP
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1 | awk '{print $2}')
    echo -e "${GREEN}✓ PHP ${PHP_VERSION} found${NC}"
else
    echo -e "${RED}✗ PHP not found${NC}"
    exit 1
fi

# Check Composer
if command -v composer &> /dev/null; then
    echo -e "${GREEN}✓ Composer found${NC}"
else
    echo -e "${RED}✗ Composer not found${NC}"
    exit 1
fi

# Check Node.js
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    echo -e "${GREEN}✓ Node.js ${NODE_VERSION} found${NC}"
else
    echo -e "${YELLOW}⚠ Node.js not found (optional for this demo)${NC}"
fi

echo ""
echo -e "${YELLOW}🗂️  Checking project files...${NC}"
echo ""

# Check key files
files=("app/Http/Controllers/AuthController.php" "app/Models/User.php" "routes/web.php" "resources/views/courses/lesson.blade.php" "resources/views/components/video-player.blade.php")

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "${GREEN}✓ $file${NC}"
    else
        echo -e "${RED}✗ $file missing${NC}"
    fi
done

echo ""
echo -e "${YELLOW}💾 Checking database...${NC}"
echo ""

# Check if .env exists
if [ -f ".env" ]; then
    echo -e "${GREEN}✓ .env file found${NC}"
    
    # Try to connect to database
    if php artisan tinker --execute "DB::connection()->getPdo();" 2>/dev/null; then
        echo -e "${GREEN}✓ Database connection successful${NC}"
    else
        echo -e "${RED}✗ Database connection failed${NC}"
        echo "Make sure your database is running and .env is configured"
    fi
else
    echo -e "${RED}✗ .env file not found${NC}"
    echo "Copy .env.example to .env and configure it"
fi

echo ""
echo -e "${YELLOW}👥 Checking test users...${NC}"
echo ""

# Check if test users exist
ADMIN_COUNT=$(php artisan tinker --execute "echo App\Models\User::where('email', 'admin@lms.test')->count();" 2>/dev/null)
if [ "$ADMIN_COUNT" = "1" ]; then
    echo -e "${GREEN}✓ Test users found${NC}"
else
    echo -e "${YELLOW}⚠ Test users not found${NC}"
    echo "Run: php artisan db:seed --class=PresentationTestUsersSeeder"
fi

echo ""
echo -e "${YELLOW}📁 Checking storage & cache...${NC}"
echo ""

# Check storage directories
if [ -d "storage/app" ] && [ -d "storage/logs" ]; then
    echo -e "${GREEN}✓ Storage directories exist${NC}"
else
    echo -e "${RED}✗ Storage directories missing${NC}"
fi

if [ -d "bootstrap/cache" ]; then
    echo -e "${GREEN}✓ Cache directory exists${NC}"
else
    echo -e "${RED}✗ Cache directory missing${NC}"
fi

echo ""
echo -e "${GREEN}=================================="
echo "✅ Verification Complete!"
echo "==================================${NC}"
echo ""
echo "📚 Next Steps:"
echo ""
echo "1. Start development server:"
echo "   php artisan serve"
echo ""
echo "2. Open in browser:"
echo "   http://localhost:8000"
echo ""
echo "3. Login with test account:"
echo "   Email: student1@lms.test"
echo "   Password: Password@123"
echo ""
echo "4. For more info, see:"
echo "   - README_PRESENTATION.md"
echo "   - IMPLEMENTATION_GUIDE.md"
echo "   - TESTING_CHECKLIST.md"
echo ""
echo "Good luck with your presentation! 🚀"
