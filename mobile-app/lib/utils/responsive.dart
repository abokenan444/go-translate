import 'package:flutter/material.dart';

/// Responsive utility class for adapting UI to different screen sizes
class Responsive {
  static late MediaQueryData _mediaQueryData;
  static late double screenWidth;
  static late double screenHeight;
  static late double blockSizeHorizontal;
  static late double blockSizeVertical;
  static late double textScaleFactor;
  static late double safeAreaHorizontal;
  static late double safeAreaVertical;
  static late double safeBlockHorizontal;
  static late double safeBlockVertical;
  static late bool isTablet;
  static late bool isPhone;
  static late bool isSmallPhone;
  static late bool isLargePhone;
  static late DeviceType deviceType;
  static late Orientation orientation;

  static void init(BuildContext context) {
    _mediaQueryData = MediaQuery.of(context);
    screenWidth = _mediaQueryData.size.width;
    screenHeight = _mediaQueryData.size.height;
    orientation = _mediaQueryData.orientation;
    textScaleFactor = _mediaQueryData.textScaler.scale(1.0);

    blockSizeHorizontal = screenWidth / 100;
    blockSizeVertical = screenHeight / 100;

    safeAreaHorizontal =
        _mediaQueryData.padding.left + _mediaQueryData.padding.right;
    safeAreaVertical =
        _mediaQueryData.padding.top + _mediaQueryData.padding.bottom;

    safeBlockHorizontal = (screenWidth - safeAreaHorizontal) / 100;
    safeBlockVertical = (screenHeight - safeAreaVertical) / 100;

    // Determine device type
    if (screenWidth >= 768) {
      deviceType = DeviceType.tablet;
      isTablet = true;
      isPhone = false;
      isSmallPhone = false;
      isLargePhone = false;
    } else if (screenWidth >= 414) {
      deviceType = DeviceType.largePhone;
      isTablet = false;
      isPhone = true;
      isSmallPhone = false;
      isLargePhone = true;
    } else if (screenWidth >= 375) {
      deviceType = DeviceType.phone;
      isTablet = false;
      isPhone = true;
      isSmallPhone = false;
      isLargePhone = false;
    } else {
      deviceType = DeviceType.smallPhone;
      isTablet = false;
      isPhone = true;
      isSmallPhone = true;
      isLargePhone = false;
    }
  }

  /// Get responsive font size
  static double fontSize(double size) {
    double scaleFactor = screenWidth / 375; // Base on iPhone X width
    scaleFactor = scaleFactor.clamp(0.85, 1.3);
    return size * scaleFactor;
  }

  /// Get responsive width
  static double width(double percentage) {
    return screenWidth * (percentage / 100);
  }

  /// Get responsive height
  static double height(double percentage) {
    return screenHeight * (percentage / 100);
  }

  /// Get responsive padding
  static EdgeInsets padding({
    double horizontal = 16,
    double vertical = 12,
  }) {
    double scale = screenWidth / 375;
    scale = scale.clamp(0.8, 1.2);
    return EdgeInsets.symmetric(
      horizontal: horizontal * scale,
      vertical: vertical * scale,
    );
  }

  /// Get responsive margin
  static EdgeInsets margin({
    double horizontal = 16,
    double vertical = 12,
  }) {
    double scale = screenWidth / 375;
    scale = scale.clamp(0.8, 1.2);
    return EdgeInsets.symmetric(
      horizontal: horizontal * scale,
      vertical: vertical * scale,
    );
  }

  /// Get responsive icon size
  static double iconSize(double size) {
    double scale = screenWidth / 375;
    scale = scale.clamp(0.85, 1.25);
    return size * scale;
  }

  /// Get responsive border radius
  static double radius(double size) {
    double scale = screenWidth / 375;
    scale = scale.clamp(0.9, 1.15);
    return size * scale;
  }

  /// Get responsive spacing
  static double spacing(double size) {
    double scale = screenWidth / 375;
    scale = scale.clamp(0.85, 1.2);
    return size * scale;
  }

  /// Check if current orientation is landscape
  static bool get isLandscape => orientation == Orientation.landscape;

  /// Check if current orientation is portrait
  static bool get isPortrait => orientation == Orientation.portrait;

  /// Get number of columns for grid based on screen width
  static int get gridColumns {
    if (screenWidth >= 1200) return 6;
    if (screenWidth >= 900) return 4;
    if (screenWidth >= 600) return 3;
    return 2;
  }

  /// Get responsive aspect ratio for cards
  static double get cardAspectRatio {
    if (isTablet) return 1.3;
    if (isLargePhone) return 1.1;
    return 1.0;
  }
}

enum DeviceType {
  smallPhone,
  phone,
  largePhone,
  tablet,
}

/// Extension for responsive sizing
extension ResponsiveSize on num {
  double get w => Responsive.width(toDouble());
  double get h => Responsive.height(toDouble());
  double get sp => Responsive.fontSize(toDouble());
  double get r => Responsive.radius(toDouble());
}

/// Widget that rebuilds when screen size changes
class ResponsiveBuilder extends StatelessWidget {
  final Widget Function(BuildContext context, Responsive responsive) builder;

  const ResponsiveBuilder({super.key, required this.builder});

  @override
  Widget build(BuildContext context) {
    Responsive.init(context);
    return builder(context, Responsive());
  }
}

/// Widget that shows different layouts based on screen size
class ResponsiveLayout extends StatelessWidget {
  final Widget mobile;
  final Widget? tablet;
  final Widget? desktop;

  const ResponsiveLayout({
    super.key,
    required this.mobile,
    this.tablet,
    this.desktop,
  });

  @override
  Widget build(BuildContext context) {
    Responsive.init(context);
    
    if (Responsive.screenWidth >= 1024 && desktop != null) {
      return desktop!;
    }
    if (Responsive.screenWidth >= 768 && tablet != null) {
      return tablet!;
    }
    return mobile;
  }
}
