<?
// ITEM : ELECTRIC SHARD
$item = array(
    'item_name' => 'Electric Shard',
    'item_token' => 'electric-shard',
    'item_game' => 'MMRPG',
    'item_group' => 'MMRPG/Items/Electric',
    'item_class' => 'item',
    'item_subclass' => 'collectible',
    'item_type' => 'electric',
    'item_description' => 'A mysterious elemental shard that radiates with the Electric type energy of a defeated support mecha.  Collect four of these items to generate a new core!',
    'item_energy' => 0,
    'item_speed' => 10,
    'item_accuracy' => 100,
    'item_value' => 0,
    'item_target' => 'auto',
    'item_function' => function($objects){
        return rpg_item::item_function_shard($objects);
    }
    );
?>