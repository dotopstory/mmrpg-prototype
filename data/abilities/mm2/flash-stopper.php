<?
// FLASH STOPPER
$ability = array(
    'ability_name' => 'Flash Stopper',
    'ability_token' => 'flash-stopper',
    'ability_game' => 'MM02',
    //'ability_group' => 'MM02/Weapons/014',
    'ability_group' => 'MM02/Weapons/009T2',
    'ability_description' => 'The user unleashes a flash of temporal energy at the opposing team, dealing damage to all enemy robots!  This ability\'s base power is divided among all targets, making it stronger the fewer there are.',
    'ability_type' => 'time',
    'ability_type2' => 'crystal',
    'ability_energy' => 8,
    'ability_damage' => 56,
    'ability_accuracy' => 98,
    'ability_function' => function($objects){

        // Extract all objects into the current scope
        extract($objects);

        // Define this ability's attachment token
        $this_attachment_token = 'ability_'.$this_ability->ability_token;
        $this_attachment_info = array(
            'class' => 'ability',
            'sticky' => true,
            'ability_token' => $this_ability->ability_token,
            'ability_image' => $this_ability->ability_token.'-2',
            'ability_frame' => 0,
            'ability_frame_animate' => array(0, 1),
            'ability_frame_offset' => array('x' => 0, 'y' => 0, 'z' => -10),
            'ability_frame_classes' => 'sprite_fullscreen '
            );

        // Count the number of active robots on the target's side of the field
        $target_robots_active = $target_player->counters['robots_active'];

        // Target the opposing robot
        $this_ability->target_options_update(array(
            'kickback' => array(-5, 0, 0),
            'frame' => 'summon',
            'success' => array(0, -10, 0, -10, $this_robot->print_name().' uses the '.$this_ability->print_name().'!')
            ));
        $this_robot->trigger_target($target_robot, $this_ability);

        // Add the black background attachment
        $target_robot->robot_attachments[$this_attachment_token] = $this_attachment_info;
        $target_robot->update_session();

        // Inflict damage on the opposing robot
        $this_ability->damage_options_update(array(
            'kind' => 'energy',
            'kickback' => array(5, 0, 0),
            'success' => array(1, -5, 0, -8, 'The '.$this_ability->print_name().' freezes time around the target!'),
            'failure' => array(2, -5, 0, -8, $this_ability->print_name().' had no effect&hellip;')
            ));
        $this_ability->recovery_options_update(array(
            'kind' => 'energy',
            'frame' => 'taunt',
            'kickback' => array(0, 0, 0),
            'success' => array(1, -5, 0, -8, 'The '.$this_ability->print_name().' freezes time around the target!'),
            'failure' => array(2, -5, 0, -8, $this_ability->print_name().' had no effect&hellip;')
            ));
        $energy_damage_amount = ceil($this_ability->ability_damage / $target_robots_active);
        $trigger_options = array('apply_modifiers' => true, 'apply_position_modifiers' => false, 'apply_stat_modifiers' => true);
        $target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount, false, $trigger_options);

        // Remove the black background attachment
        unset($target_robot->robot_attachments[$this_attachment_token]);
        $target_robot->update_session();

        // Loop through the target's benched robots, inflicting half base damage to each
        $backup_robots_active = $target_player->values['robots_active'];
        foreach ($backup_robots_active AS $key => $info){
            if ($info['robot_id'] == $target_robot->robot_id){ continue; }
            $this_ability->ability_results_reset();
            $temp_target_robot = rpg_game::get_robot($this_battle, $target_player, $info);
            $temp_target_robot->robot_attachments[$this_attachment_token] = $this_attachment_info;
            $temp_target_robot->update_session();
            //$energy_damage_amount = ceil($this_ability->ability_damage / $target_robots_active);
            $energy_damage_amount = $this_ability->ability_damage;
            $temp_target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount, false, $trigger_options);
            unset($temp_target_robot->robot_attachments[$this_attachment_token]);
            $temp_target_robot->update_session();
        }

        // Loop through all robots on the target side and disable any that need it
        $target_robots_active = $target_player->get_robots();
        foreach ($target_robots_active AS $key => $robot){
            if ($robot->robot_id == $target_robot->robot_id){ $temp_target_robot = $target_robot; }
            else { $temp_target_robot = $robot; }
            if (($temp_target_robot->robot_energy < 1 || $temp_target_robot->robot_status == 'disabled')
                && empty($temp_target_robot->flags['apply_disabled_state'])){
                $temp_target_robot->trigger_disabled($this_robot);
            }
        }

        // Return true on success
        return true;


        },
    'ability_function_onload' => function($objects){

        // Extract all objects into the current scope
        extract($objects);

        // Calculate the base damage for this ability based on the number of target robots
        $temp_new_damage_amount = !empty($target_player->counters['robots_active']) ? round($this_ability->ability_base_damage / $target_player->counters['robots_active']) : $this_ability->ability_base_damage;
        if ($temp_new_damage_amount < 1){ $temp_new_damage_amount = 1; }

        // Update this ability's base damage with the new amount and save
        $this_ability->ability_damage = $temp_new_damage_amount;
        $this_ability->update_session();

        // Return true on success
        return true;

        }
    );
?>