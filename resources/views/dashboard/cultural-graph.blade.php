@extends('dashboard.layout')

@section('title', 'Cultural Memory Graph')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-project-diagram mr-3 text-indigo-600"></i>
                Cultural Memory Graph
            </h3>
            <button @click="refreshGraph()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
        </div>

        <div class="mb-4 flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Type</label>
                <select x-model="filterType" @change="applyFilter()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="all">All Types</option>
                    <option value="memory">Memories</option>
                    <option value="language">Languages</option>
                    <option value="culture">Cultures</option>
                    <option value="term">Glossary Terms</option>
                    <option value="brand_voice">Brand Voices</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" x-model="searchQuery" @input="applyFilter()" placeholder="Search nodes..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
        </div>

        <div id="graph-container" style="width: 100%; height: 600px; border: 1px solid #e5e7eb; border-radius: 0.5rem; background: #f9fafb;"></div>

        <div class="mt-4 grid grid-cols-5 gap-3 text-sm">
            <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                <span>Memory</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                <span>Language</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-purple-500 mr-2"></div>
                <span>Culture</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-orange-500 mr-2"></div>
                <span>Term</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 rounded-full bg-pink-500 mr-2"></div>
                <span>Brand Voice</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Graph Statistics</h4>
        <div class="grid grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600" x-text="stats.nodes"></div>
                <div class="text-sm text-gray-600">Total Nodes</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600" x-text="stats.links"></div>
                <div class="text-sm text-gray-600">Connections</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600" x-text="stats.memories"></div>
                <div class="text-sm text-gray-600">Memories</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-indigo-600" x-text="stats.languages"></div>
                <div class="text-sm text-gray-600">Languages</div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vis-network/standalone/umd/vis-network.min.js"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('graphData', () => ({
        filterType: 'all',
        searchQuery: '',
        rawNodes: [],
        rawLinks: [],
        network: null,
        stats: {
            nodes: 0,
            links: 0,
            memories: 0,
            languages: 0
        },

        init() {
            this.loadGraph();
        },

        async loadGraph() {
            try {
                const response = await fetch('/api/kbm/graph', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    this.rawNodes = data.data.nodes || [];
                    this.rawLinks = data.data.links || [];
                    this.updateStats();
                    this.renderGraph();
                }
            } catch (error) {
                console.error('Failed to load graph:', error);
            }
        },

        updateStats() {
            this.stats.nodes = this.rawNodes.length;
            this.stats.links = this.rawLinks.length;
            this.stats.memories = this.rawNodes.filter(n => n.type === 'memory').length;
            this.stats.languages = this.rawNodes.filter(n => n.type === 'language').length;
        },

        applyFilter() {
            this.renderGraph();
        },

        renderGraph() {
            let filteredNodes = this.rawNodes;

            if (this.filterType !== 'all') {
                filteredNodes = filteredNodes.filter(n => n.type === this.filterType);
            }

            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filteredNodes = filteredNodes.filter(n => 
                    n.label.toLowerCase().includes(query) || n.id.toLowerCase().includes(query)
                );
            }

            const colorMap = {
                memory: '#3b82f6',
                language: '#10b981',
                culture: '#8b5cf6',
                term: '#f97316',
                brand_voice: '#ec4899'
            };

            const nodes = new vis.DataSet(filteredNodes.map(n => ({
                id: n.id,
                label: n.label,
                color: colorMap[n.type] || '#6b7280',
                shape: 'dot',
                size: n.type === 'memory' ? 15 : 20
            })));

            const filteredNodeIds = new Set(filteredNodes.map(n => n.id));
            const links = new vis.DataSet(this.rawLinks.filter(l => 
                filteredNodeIds.has(l.source) && filteredNodeIds.has(l.target)
            ).map(l => ({
                from: l.source,
                to: l.target,
                label: l.label,
                arrows: 'to',
                color: { color: '#cbd5e1', opacity: 0.6 }
            })));

            const container = document.getElementById('graph-container');
            const data = { nodes, edges: links };
            const options = {
                physics: {
                    enabled: true,
                    barnesHut: {
                        gravitationalConstant: -2000,
                        centralGravity: 0.3,
                        springLength: 95
                    }
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 200
                }
            };

            if (this.network) {
                this.network.setData(data);
            } else {
                this.network = new vis.Network(container, data, options);
            }
        },

        refreshGraph() {
            this.loadGraph();
        }
    }));
});
</script>

<div x-data="graphData()"></div>
@endsection
