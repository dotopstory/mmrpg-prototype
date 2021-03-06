<?
// ENERGY BOOST
$ability = array(
    'ability_name' => 'Energy Boost',
    'ability_token' => 'energy-boost',
    'ability_game' => 'MMRPG',
    'ability_group' => 'MMRPG/Support/Energy',
    'ability_description' => 'The user manually repairs its own body to restore life energy by up to {RECOVERY}%!',
    'ability_energy' => 4,
    'ability_recovery' => 20,
    'ability_recovery_percent' => true,
    'ability_accuracy' => 100,
    'ability_function' => function($objects){

        // Extract all objects into the current scope
        extract($objects);

        // Target this robot's self
        $this_ability->target_options_update(array(
            'frame' => 'summon',
            'success' => array(0, 0, 10, -10, $this_robot->print_name().' uses '.$this_ability->print_name().'!')
            ));
        $this_robot->trigger_target($this_robot, $this_ability);

        // If this ability is being used by a special support robot that allows targetting
        if ($this_robot->player_id == $target_robot->player_id){

            // Increase this robot's energy stat
            $this_ability->recovery_options_update(array(
                'kind' => 'energy',
                'percent' => true,
                'frame' => 'taunt',
                'success' => array(0, -2, 0, -10, $target_robot->print_name().'&#39;s energy was restored!'),
                'failure' => array(9, -2, 0, -10, $target_robot->print_name().'&#39;s energy was not affected&hellip;')
                ));
            $energy_recovery_amount = ceil($target_robot->robot_base_energy * ($this_ability->ability_recovery / 100));
            $target_robot->trigger_recovery($target_robot, $this_ability, $energy_recovery_amount);

        }
        // Otherwise if targetting a team mate
        else {

            // Increase the target robot's energy stat
            $this_ability->recovery_options_update(array(
                'kind' => 'energy',
                'percent' => true,
                'frame' => 'taunt',
                'success' => array(0, -2, 0, -10, $this_robot->print_name().'&#39;s energy was restored!'),
                'failure' => array(9, -2, 0, -10, $this_robot->print_name().'&#39;s energy was not affected&hellip;')
                ));
            $energy_recovery_amount = ceil($this_robot->robot_base_energy * ($this_ability->ability_recovery / 100));
            $this_robot->trigger_recovery($this_robot, $this_ability, $energy_recovery_amount);

        }

        // Return true on success
        return true;

    },
    'ability_function_onload' => function($objects){

        // Extract all objects into the current scope
        extract($objects);

        // If used by support robot OR the has a Target Module, allow bench targetting
        $temp_support_robots = array('roll', 'disco', 'rhythm');
        if (in_array($this_robot->robot_token, $temp_support_robots)
            || $this_robot->has_item('target-module')){ $this_ability->set_target('select_this'); }
        else { $this_ability->reset_target(); }

        // Return true on success
        return true;

        }
    );
?>