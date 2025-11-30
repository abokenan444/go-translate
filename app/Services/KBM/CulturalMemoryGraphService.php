<?php

namespace App\Services\KBM;

use Illuminate\Support\Facades\DB;

class CulturalMemoryGraphService
{
    public function buildGraph(?int $userId = null): array
    {
        $nodes = [];
        $links = [];

        $addNode = function($id, $label, $type) use (&$nodes) {
            if (!isset($nodes[$id])) {
                $nodes[$id] = [ 'id' => $id, 'label' => $label, 'type' => $type ];
            }
        };
        $addLink = function($source, $target, $label) use (&$links) {
            $links[] = [ 'source' => $source, 'target' => $target, 'label' => $label ];
        };

        $memories = DB::table('cultural_memories')
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->select(['id','user_id','source_language','target_language','target_culture'])
            ->limit(1000)
            ->get();

        foreach ($memories as $m) {
            $mid = 'mem_'.$m->id;
            $addNode($mid, 'Memory #'.$m->id, 'memory');
            if ($m->source_language) { $addNode('lang_'.$m->source_language, strtoupper($m->source_language), 'language'); $addLink($mid, 'lang_'.$m->source_language, 'source'); }
            if ($m->target_language) { $addNode('lang_'.$m->target_language, strtoupper($m->target_language), 'language'); $addLink($mid, 'lang_'.$m->target_language, 'target'); }
            if ($m->target_culture) { $addNode('cult_'.$m->target_culture, $m->target_culture, 'culture'); $addLink($mid, 'cult_'.$m->target_culture, 'culture'); }
        }

        $terms = DB::table('glossary_terms')->when($userId, fn($q) => $q->where('user_id', $userId))->select(['id','language','term','preferred'])->limit(200)->get();
        foreach ($terms as $t) {
            $tid = 'term_'.$t->id;
            $addNode($tid, $t->term.' → '.$t->preferred, 'term');
            if ($t->language) { $addNode('lang_'.$t->language, strtoupper($t->language), 'language'); $addLink($tid, 'lang_'.$t->language, 'lang'); }
        }

        $voices = DB::table('brand_voices')->when($userId, fn($q) => $q->where('user_id', $userId))->select(['id','name'])->limit(100)->get();
        foreach ($voices as $v) {
            $vid = 'voice_'.$v->id;
            $addNode($vid, $v->name, 'brand_voice');
        }

        return [
            'nodes' => array_values($nodes),
            'links' => $links,
        ];
    }
}
