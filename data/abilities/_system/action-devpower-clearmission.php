<?
// ACTION : DEVPOWER CLEAR MISSION
$ability = array(
	'ability_name' => 'DevPower : Clear Mission',
	'ability_token' => 'action-devpower-clearmission',
	'ability_class' => 'system',
	'ability_image' => 'ability',
	'ability_description' => 'The developer unleashes a totally fair and balanced attack against the entire opposing team that disables every single one of the target robots without fail.',
	'ability_energy' => 0,
	'ability_damage' => 0,
	'ability_speed' => 10,
	'ability_accuracy' => 100,
	'ability_function' => function($objects){

        // Extract all objects into the current scope
        extract($objects);

        // Ensure this robot stays in the summon position for the duration of the attack
        $this_robot->robot_frame = 'summon';
        $this_robot->update_session();

       	// Print out the DEVPOWER header so we know it's serious
		$this_battle->events_create(false, false, 'DEVPOWER', '<strong class="ability_name ability_type ability_type_nature_shield">DevPower : Clear Mission!</strong>');

        // Count the number of active robots on the target's side of the field
        $target_robots_active = $target_player->counters['robots_active'];

        // Inflict damage on the opposing robot
        $damage_type = '';
        if ($this_robot->robot_core === 'copy'){ $damage_type = !empty($target_robot->robot_weaknesses[0]) ? $target_robot->robot_weaknesses[0] : ''; }
        elseif (!empty($this_robot->robot_core)){ $damage_type = $this_robot->robot_core; }
        if (in_array($damage_type, $target_robot->robot_affinities) || in_array($damage_type, $target_robot->robot_immunities)){ $damage_type = ''; }
        $this_ability->damage_options_update(array(
            'kind' => 'energy',
            'type' => $damage_type,
            'frame' => 'damage',
            'modifiers' => false,
            'success' => array(0, 0, 0, 0, 'The <strong class="ability_name ability_type type_'.(!empty($damage_type) ? $damage_type : 'none').'">DevPower</strong> cleared out '.$target_robot->print_name().'!')
            ));
        $this_ability->recovery_options_update(array(
            'kind' => 'energy',
            'type' => $damage_type,
            'frame' => 'damage',
            'modifiers' => false,
            'success' => array(0, 0, 0, 0, 'The <strong class="ability_name ability_type type_'.(!empty($damage_type) ? $damage_type : 'none').'">DevPower</strong> cleared out '.$target_robot->print_name().'!')
            ));
        $energy_damage_amount = $target_robot->robot_base_energy;
        $trigger_options = array('apply_modifiers' => true, 'apply_position_modifiers' => false, 'apply_stat_modifiers' => false);
        $target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount, false, $trigger_options);

        // Loop through the target's benched robots, inflicting damage to each
        $backup_target_robots_active = $target_player->values['robots_active'];
        foreach ($backup_target_robots_active AS $key => $info){
            if ($info['robot_id'] == $target_robot->robot_id){ continue; }
            $temp_target_robot = rpg_game::get_robot($this_battle, $target_player, $info);
            $this_ability->ability_results_reset();
            $damage_type = '';
            if ($this_robot->robot_core === 'copy'){ $damage_type = !empty($temp_target_robot->robot_weaknesses[0]) ? $temp_target_robot->robot_weaknesses[0] : ''; }
            elseif (!empty($this_robot->robot_core)){ $damage_type = $this_robot->robot_core; }
            if (in_array($damage_type, $temp_target_robot->robot_affinities) || in_array($damage_type, $temp_target_robot->robot_immunities)){ $damage_type = ''; }
            $this_ability->damage_options_update(array(
	            'kind' => 'energy',
                'type' => $damage_type,
	            'frame' => 'damage',
	            'modifiers' => false,
	            'success' => array(0, 0, 0, 0, 'The <strong class="ability_name ability_type type_'.(!empty($damage_type) ? $damage_type : 'none').'">DevPower</strong> cleared out '.$temp_target_robot->print_name().'!')
                ));
            $this_ability->recovery_options_update(array(
	            'kind' => 'energy',
                'type' => $damage_type,
	            'frame' => 'damage',
	            'modifiers' => false,
	            'success' => array(0, 0, 0, 0, 'The <strong class="ability_name ability_type type_'.(!empty($damage_type) ? $damage_type : 'none').'">DevPower</strong> cleared out '.$temp_target_robot->print_name().'!')
                ));
            $energy_damage_amount = $temp_target_robot->robot_base_energy;
            $temp_target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount, false, $trigger_options);
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


        }
	);
?>