<?
// STICKY BOND
$ability = array(
  'ability_name' => 'Sticky Bond',
  'ability_token' => 'sticky-bond',
  'ability_game' => 'MM00',
  'ability_description' => 'The user attaches a large glob of sticky glue to the target that deals damage and prevents switching for three turns!',
  'ability_damage' => 8,
  'ability_accuracy' => 94,
  'ability_function' => function($objects){

    // Extract all objects into the current scope
    extract($objects);

    // Target the opposing robot
    $this_ability->target_options_update(array(
      'frame' => 'shoot',
      'success' => array(0, 75, 0, 10, $this_robot->print_name().' uses the '.$this_ability->print_name().'!')
      ));
    $this_robot->trigger_target($target_robot, $this_ability);

    // Inflict damage on the opposing robot
    $this_ability->damage_options_update(array(
      'kind' => 'energy',
      'kickback' => array(10, 0, 0),
      'success' => array(1, -55, 0, 10, 'The '.$this_ability->print_name().' hit the target!'),
      'failure' => array(1, -75, 0, -10, 'The '.$this_ability->print_name().' missed the target&hellip;')
      ));
    $this_ability->recovery_options_update(array(
      'kind' => 'energy',
      'frame' => 'taunt',
      'kickback' => array(10, 0, 0),
      'success' => array(1, -35, 0, 10, 'The '.$this_ability->print_name().' was absorbed by the target!'),
      'failure' => array(1, -75, 0, -10, 'The '.$this_ability->print_name().' missed the target&hellip;')
      ));
    $energy_damage_amount = $this_ability->ability_damage;
    $target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount);

    // Return true on success
    return true;

  }
  );
?>