#!/bin/bash
# CulturalTranslate Mobile App - iOS Build Script
# ==========================================
# هذا السكريبت يقوم ببناء التطبيق لنظام iOS (يتطلب macOS)

echo "======================================"
echo "  CulturalTranslate iOS Builder"
echo "======================================"
echo ""

# التأكد من وجود Flutter
if ! command -v flutter &> /dev/null; then
    echo "ERROR: Flutter not found in PATH"
    exit 1
fi

# التأكد من أننا على macOS
if [[ "$OSTYPE" != "darwin"* ]]; then
    echo "ERROR: iOS build requires macOS"
    exit 1
fi

# الانتقال إلى مجلد التطبيق
cd "$(dirname "$0")"

echo "[1/6] Cleaning previous builds..."
flutter clean

echo "[2/6] Getting dependencies..."
flutter pub get

echo "[3/6] Installing CocoaPods dependencies..."
cd ios
pod install --repo-update
cd ..

echo "[4/6] Checking for issues..."
flutter analyze --no-fatal-infos

# بناء iOS (بدون توقيع للتطوير)
echo ""
echo "======================================"
echo "[5/6] Building iOS App (No Codesign)..."
echo "======================================"

flutter build ios --release --no-codesign

if [ $? -eq 0 ]; then
    echo "iOS build completed successfully!"
else
    echo "iOS build failed!"
    exit 1
fi

# بناء IPA (يتطلب توقيع)
echo ""
echo "======================================"
echo "[6/6] Building IPA..."
echo "======================================"

# محاولة بناء IPA مع export options
cat > ios/ExportOptions.plist << EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>method</key>
    <string>development</string>
    <key>teamID</key>
    <string>YOUR_TEAM_ID</string>
    <key>signingStyle</key>
    <string>automatic</string>
    <key>stripSwiftSymbols</key>
    <true/>
    <key>uploadBitcode</key>
    <false/>
    <key>uploadSymbols</key>
    <true/>
</dict>
</plist>
EOF

flutter build ipa --release --export-options-plist=ios/ExportOptions.plist 2>/dev/null

if [ $? -eq 0 ]; then
    echo "IPA built successfully!"
    
    # نسخ IPA إلى مجلد مخصص
    mkdir -p build/release
    cp build/ios/ipa/*.ipa build/release/CulturalTranslate.ipa 2>/dev/null
    
    echo "IPA copied to: build/release/CulturalTranslate.ipa"
else
    echo ""
    echo "NOTE: IPA build requires proper Apple Developer signing."
    echo "To build IPA manually:"
    echo "  1. Open ios/Runner.xcworkspace in Xcode"
    echo "  2. Select your team in Signing & Capabilities"
    echo "  3. Archive and export the IPA"
    echo ""
    echo "Or use fastlane for automated builds:"
    echo "  cd ios && fastlane build"
fi

echo ""
echo "======================================"
echo "  Build Complete!"
echo "======================================"
echo ""
echo "Build artifacts:"
echo "  iOS App: build/ios/iphoneos/Runner.app"
echo ""
echo "For App Store submission:"
echo "  1. Open ios/Runner.xcworkspace in Xcode"
echo "  2. Select 'Any iOS Device' as target"
echo "  3. Product -> Archive"
echo "  4. Distribute App -> App Store Connect"
echo ""
