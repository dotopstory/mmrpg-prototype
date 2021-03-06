<?
// BLIZZARD ATTACK
$ability = array(
    'ability_name' => 'Blizzard Attack',
    'ability_token' => 'blizzard-attack',
    'ability_game' => 'MM06',
    //'ability_group' => 'MM06/Weapons/041',
    'ability_group' => 'MM06/Weapons/041T1',
    'ability_image_sheets' => 2,
    'ability_description' => 'The user summons a powerful blizzard that covers the screen and damages all robots on the opponent\'s side of the field!',
    'ability_type' => 'freeze',
    'ability_energy' => 4,
    'ability_damage' => 12,
    'ability_accuracy' => 100,
    'ability_function' => function($objects){

        // Extract all objects into the current scope
        extract($objects);

        // Define this ability's attachment token
        $this_attachment_token = 'ability_'.$this_ability->ability_token;
        $this_attachment_info = array(
            'class' => 'ability',
            'ability_token' => $this_ability->ability_token,
            'ability_image' => $this_ability->ability_base_image,
            'ability_frame' => 2,
            'ability_frame_animate' => array(2, 3),
            'ability_frame_offset' => array('x' => 0, 'y' => 0, 'z' => 100),
            'ability_frame_classes' => ' '
            );

        // Change the image to the full-screen rain effect
        $this_ability->ability_image = $this_ability->ability_base_image;
        $this_ability->ability_frame_classes = '';
        $this_ability->update_session();

        // Target the opposing robot
        $this_ability->target_options_update(array(
            'frame' => 'summon',
            'success' => array(0, 0, 100, 10, $this_robot->print_name().' summons a '.$this_ability->print_name().'!')
            ));
        $this_robot->trigger_target($target_robot, $this_ability, array('prevent_default_text' => true, 'prevent_stats_text' => true));

        // Change the image to the full-screen rain effect
        $this_ability->ability_image = $this_ability->ability_base_image.'-2';
        $this_ability->ability_frame_classes = 'sprite_fullscreen ';
        $this_ability->update_session();


        // -- DAMAGE TARGETS -- //

        // Inflict damage on the opposing robot
        $num_hits_counter = 0;
        $this_robot->set_frame('throw');
        $target_robot->set_attachment($this_attachment_token.'_fx', $this_attachment_info);
        $this_ability->damage_options_update(array(
            'kind' => 'energy',
            'modifiers' => true,
            'kickback' => array(5, 0, 0),
            'success' => array(0, -5, 0, 99, 'The hailstorm battered the target with ice!'),
            'failure' => array(0, -5, 0, -10,'The '. $this_ability->print_name().' missed the first target&hellip;')
            ));
        $this_ability->recovery_options_update(array(
            'kind' => 'energy',
            'modifiers' => true,
            'frame' => 'taunt',
            'kickback' => array(5, 0, 0),
            'success' => array(0, -5, 0, 9, 'The hailstorm was absorbed by the target!'),
            'failure' => array(0, -5, 0, 9, 'The '.$this_ability->print_name().' had no effect on the first target&hellip;')
            ));
        $energy_damage_amount = $this_ability->ability_damage;
        $trigger_options = array('apply_modifiers' => true, 'apply_position_modifiers' => false, 'apply_stat_modifiers' => true);
        $target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount, false, $trigger_options);
        $target_robot->unset_attachment($this_attachment_token.'_fx');
        $num_hits_counter++;

        // Loop through the target's benched robots, inflicting damage to each
        $backup_target_robots_active = $target_player->values['robots_active'];
        foreach ($backup_target_robots_active AS $key => $info){
            if ($info['robot_id'] == $target_robot->robot_id){ continue; }
            $this_robot->set_frame($num_hits_counter % 2 === 0 ? 'defend' : 'taunt');
            $temp_target_robot = rpg_game::get_robot($this_battle, $target_player, $info);
            $temp_target_robot->set_attachment($this_attachment_token.'_fx', $this_attachment_info);
            $this_ability->ability_results_reset();
            $temp_positive_word = rpg_battle::random_positive_word();
            $temp_negative_word = rpg_battle::random_negative_word();
            $this_ability->damage_options_update(array(
                'kind' => 'energy',
                'modifiers' => true,
                'kickback' => array(5, 0, 0),
                'success' => array(($key % 2), -5, 0, 99, ($target_player->player_side === 'right' ? $temp_positive_word : $temp_negative_word).' The attack hit another robot!'),
                'failure' => array(($key % 2), -5, 0, 99, 'The attack had no effect on '.$temp_target_robot->print_name().'&hellip;')
                ));
            $this_ability->recovery_options_update(array(
                'kind' => 'energy',
                'modifiers' => true,
                'frame' => 'taunt',
                'kickback' => array(5, 0, 0),
                'success' => array(($key % 2), -5, 0, 9, ($target_player->player_side === 'right' ? $temp_negative_word : $temp_positive_word).' The attack was absorbed by the target!'),
                'failure' => array(($key % 2), -5, 0, 9, 'The attack had no effect on '.$temp_target_robot->print_name().'&hellip;')
                ));
            $energy_damage_amount = $this_ability->ability_damage;
            $temp_target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount, false, $trigger_options);
            $temp_target_robot->unset_attachment($this_attachment_token.'_fx');
            $num_hits_counter++;
        }

        // Return the user to their base frame
        $this_robot->set_frame('base');

        // REMOVE ATTACHMENTS
        if (true){

            // Attach this ability to all robots on this player's side of the field
            $backup_robots_active = $this_player->values['robots_active'];
            $backup_robots_active_count = !empty($backup_robots_active) ? count($backup_robots_active) : 0;
            if ($backup_robots_active_count > 0){
                $this_key = 0;
                foreach ($backup_robots_active AS $key => $info){
                    if ($info['robot_id'] == $this_robot->robot_id){ continue; }
                    $info2 = array('robot_id' => $info['robot_id'], 'robot_token' => $info['robot_token']);
                    $temp_this_robot = rpg_game::get_robot($this_battle, $this_player, $info2);
                    $temp_this_robot->robot_frame = 'base';
                    unset($temp_this_robot->robot_attachments[$this_attachment_token]);
                    $temp_this_robot->update_session();
                    $this_key++;
                }
            }

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

        // Change the image to the full-screen rain effect
        $this_ability->ability_image = $this_ability->ability_base_image;
        $this_ability->ability_frame_classes = '';
        $this_ability->update_session();

        // Return true on success
        return true;


        }
    );
?>