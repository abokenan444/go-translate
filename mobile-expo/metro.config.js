const path = require('path');
const { getDefaultConfig } = require('expo/metro-config');

/**
 * Force Metro to resolve modules from THIS project's node_modules.
 * This prevents accidental resolution from parent folders like:
 *   C:\Users\YASSE\node_modules
 */
const config = getDefaultConfig(__dirname);

// Metro (and dependencies like metro-file-map) sometimes build paths using
// path.join(root, absolutePath). On Windows, joining an absolute drive path can
// produce malformed paths like: C:\c:\Users\...
// Keeping a consistent, normalized drive-letter casing helps avoid that.
function normalizeWindowsPath(absPath) {
	const resolved = path.resolve(absPath);
	return resolved.replace(/^([a-z]):\\/i, (match, driveLetter) => {
		return `${driveLetter.toUpperCase()}:\\`;
	});
}

const projectRoot = normalizeWindowsPath(__dirname);
const projectNodeModules = normalizeWindowsPath(
	path.resolve(__dirname, 'node_modules'),
);

config.projectRoot = projectRoot;
config.watchFolders = [projectRoot];
config.resolver.nodeModulesPaths = [projectNodeModules];
config.resolver.disableHierarchicalLookup = true;

module.exports = config;
