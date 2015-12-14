<?
// SWIFT OVERDRIVE
$ability = array(
  'ability_name' => 'Swift Overdrive',
  'ability_token' => 'swift-overdrive',
  'ability_game' => 'MMRPG',
  'ability_group' => 'MMRPG/Weapons/11/Swift',
  'ability_description' => 'The user releases all of their stored weapon energy at once in a powerful storm of quick shots, dealing Swift type damage to all targets on the opponent\'s side of the field.  This ability\'s power is directly proportionate to the amount of life energy the user has lost, making it most effective when used in critical condition.',
  'ability_type' => 'swift',
  'ability_energy' => 0,
  'ability_energy_percent' => true,
  'ability_accuracy' => 100,
  'ability_function' => function($objects){

    // Call the common overdrive function from here
    return rpg_ability::ability_function_overdrive($objects, 'quick', 'crashed into', 'buffered');

    },
  'ability_function_onload' => function($objects){

    // Call the common overdrive onload function from here
    return rpg_ability::ability_function_onload_overdrive($objects);

    }
  );
?>