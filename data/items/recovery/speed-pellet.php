<?
// ITEM : SPEED PELLET
$item = array(
    'item_name' => 'Speed Pellet',
    'item_token' => 'speed-pellet',
    'item_game' => 'MMRPG',
    'item_group' => 'MM00/Items/Speed',
    'item_class' => 'item',
    'item_subclass' => 'consumable',
    'item_type' => 'speed',
    'item_description' => 'A small mobility pellet that sharply raises the speed stat of one robot on the user\'s side of the field.',
    'item_energy' => 0,
    'item_speed' => 10,
    'item_recovery' => 2,
    'item_accuracy' => 100,
    'item_price' => 900,
    'item_target' => 'select_this',
    'item_function' => function($objects){

        // Call the global stat booster item function
        return rpg_item::item_function_stat_booster($objects);

        }
    );
?>