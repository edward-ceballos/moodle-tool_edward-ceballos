<?php

class behat_tool_edward_generator extends behat_generator_base {
 
    protected function get_creatable_entities(): array {
        return [
            'things' => [
                'datagenerator' => 'thing',
                'required' => ['name']
            ],
        ];
    }
}