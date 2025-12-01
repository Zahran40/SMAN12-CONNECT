#!/bin/bash
# ============================================
# Script untuk Pre-Deploy Check
# Jalankan sebelum upload ke Hostinger
# ============================================

echo "🔍 SMAN12-CONNECT Pre-Deployment Check"
echo "========================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

ERRORS=0
WARNINGS=0

# 1. Check .env.production exists
echo "📄 Checking .env.production..."
if [ -f ".env.production" ]; then
    echo -e "${GREEN}✓${NC} .env.production found"
else
    echo -e "${RED}✗${NC} .env.production not found"
    ((ERRORS++))
fi

# 2. Check required folders
echo ""
echo "📁 Checking folder structure..."
REQUIRED_DIRS=("app" "bootstrap" "config" "database" "public" "resources" "routes" "storage")
for dir in "${REQUIRED_DIRS[@]}"; do
    if [ -d "$dir" ]; then
        echo -e "${GREEN}✓${NC} $dir/ exists"
    else
        echo -e "${RED}✗${NC} $dir/ not found"
        ((ERRORS++))
    fi
done

# 3. Check composer.json
echo ""
echo "📦 Checking dependencies..."
if [ -f "composer.json" ]; then
    echo -e "${GREEN}✓${NC} composer.json found"
else
    echo -e "${RED}✗${NC} composer.json not found"
    ((ERRORS++))
fi

if [ -f "package.json" ]; then
    echo -e "${GREEN}✓${NC} package.json found"
else
    echo -e "${RED}✗${NC} package.json not found"
    ((ERRORS++))
fi

# 4. Check if node_modules exists (should NOT upload)
if [ -d "node_modules" ]; then
    echo -e "${YELLOW}⚠${NC} node_modules/ exists (will need to exclude on upload)"
    ((WARNINGS++))
fi

# 5. Check if vendor exists (should NOT upload)
if [ -d "vendor" ]; then
    echo -e "${YELLOW}⚠${NC} vendor/ exists (will need to exclude on upload)"
    ((WARNINGS++))
fi

# 6. Check storage permissions
echo ""
echo "🔒 Checking permissions..."
if [ -w "storage" ]; then
    echo -e "${GREEN}✓${NC} storage/ is writable"
else
    echo -e "${RED}✗${NC} storage/ is not writable"
    ((ERRORS++))
fi

if [ -w "bootstrap/cache" ]; then
    echo -e "${GREEN}✓${NC} bootstrap/cache/ is writable"
else
    echo -e "${RED}✗${NC} bootstrap/cache/ is not writable"
    ((ERRORS++))
fi

# 7. Check .htaccess
echo ""
echo "⚙️ Checking configuration files..."
if [ -f ".htaccess" ]; then
    echo -e "${GREEN}✓${NC} .htaccess found in root"
else
    echo -e "${YELLOW}⚠${NC} .htaccess not found (optional)"
    ((WARNINGS++))
fi

if [ -f "public/.htaccess" ]; then
    echo -e "${GREEN}✓${NC} public/.htaccess found"
else
    echo -e "${RED}✗${NC} public/.htaccess not found"
    ((ERRORS++))
fi

# 8. Check database backup
echo ""
echo "💾 Checking backups..."
if [ -d "backups" ]; then
    BACKUP_COUNT=$(ls -1 backups/*.sql 2>/dev/null | wc -l)
    if [ $BACKUP_COUNT -gt 0 ]; then
        echo -e "${GREEN}✓${NC} Found $BACKUP_COUNT database backup(s)"
    else
        echo -e "${YELLOW}⚠${NC} No database backups found"
        ((WARNINGS++))
    fi
else
    echo -e "${YELLOW}⚠${NC} backups/ folder not found"
    ((WARNINGS++))
fi

# Summary
echo ""
echo "========================================"
echo "📊 Summary"
echo "========================================"

if [ $ERRORS -eq 0 ] && [ $WARNINGS -eq 0 ]; then
    echo -e "${GREEN}✅ All checks passed! Ready for deployment.${NC}"
    exit 0
elif [ $ERRORS -eq 0 ]; then
    echo -e "${YELLOW}⚠️  $WARNINGS warning(s) found. Review before deployment.${NC}"
    exit 0
else
    echo -e "${RED}❌ $ERRORS error(s) found. Fix before deployment.${NC}"
    if [ $WARNINGS -gt 0 ]; then
        echo -e "${YELLOW}⚠️  Also $WARNINGS warning(s) found.${NC}"
    fi
    exit 1
fi
