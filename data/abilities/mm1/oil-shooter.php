<?
// OIL SHOOTER
$ability = array(
    'ability_name' => 'Oil Shooter',
    'ability_token' => 'oil-shooter',
    'ability_game' => 'MM01',
    'ability_group' => 'MM01/Weapons/00B',
    'ability_description' => 'The user fires a large blob of crude oil at the target\'s feet to deal damage and make their position on the field vulnerable to Flame and Explode type attacks!',
    'ability_type' => 'earth',
    'ability_energy' => 4,
    'ability_damage' => 16,
    'ability_accuracy' => 96,
    'ability_target' => 'select_target',
    'ability_function' => function($objects){

        // Extract all objects into the current scope
        extract($objects);

        // Define this ability's attachment token
        $static_attachment_key = $target_robot->get_static_attachment_key();
        $static_attachment_duration = 6;
        $this_attachment_token = 'ability_'.$this_ability->ability_token.'_'.$static_attachment_key;
        $this_attachment_info = array(
            'class' => 'ability',
            'sticky' => true,
            'ability_id' => $this_ability->ability_id,
            'ability_token' => $this_ability->ability_token,
            'attachment_duration' => $static_attachment_duration,
            'attachment_token' => $this_attachment_token,
            'attachment_sticky' => true,
            'attachment_damage_input_booster_flame' => 2.0,
            'attachment_damage_input_booster_explode' => 2.0,
            'attachment_weaknesses' => array('flame', 'explode'),
            'attachment_create' => array(
                'trigger' => 'special',
                'kind' => '',
                'percent' => true,
                'frame' => 'defend',
                'rates' => array(100, 0, 0),
                'success' => array(9, 0, -10, -8, $target_robot->print_name().' found '.$target_robot->get_pronoun('possessive').' in a puddle of crude oil!<br /> '.$target_robot->print_name().'\'s position on the field is now vulnerable to Flame and Explode!'),
                'failure' => array(9, 0, -10, -8, $target_robot->print_name().' found '.$target_robot->get_pronoun('possessive').' in a puddle of crude oil!<br /> '.$target_robot->print_name().'\'s position on the field is now vulnerable to Flame and Explode!')
                ),
            'attachment_destroy' => array(
                'trigger' => 'special',
                'kind' => '',
                'type' => '',
                'percent' => true,
                'modifiers' => false,
                'frame' => 'taunt',
                'rates' => array(100, 0, 0),
                'success' => array(9, 0, -9999, 0,
                    'The '.$this_ability->print_name().'\'s puddle of oil faded away...<br /> '.
                    'This position on the field isn\'t vulnerable to Flame and Explode anymore!'
                    ),
                'failure' => array(9, 0, -9999, 0,
                    'The '.$this_ability->print_name().'\'s puddle of oil faded away!<br /> '.
                    'This position on the field isn\'t vulnerable to Flame and Explode anymore!'
                    )
                ),
            'ability_frame' => 1,
            'ability_frame_animate' => array(1, 2),
            'ability_frame_offset' => array('x' => 0, 'y' => -10, 'z' => -8)
            );

        // Target the opposing robot
        $this_ability->target_options_update(array(
            'frame' => 'shoot',
            'success' => array(0, 125, 5, 10, $this_robot->print_name().' fires the '.$this_ability->print_name().'!')
            ));
        $this_robot->trigger_target($target_robot, $this_ability);

        // Inflict damage on the opposing robot
        $this_ability->damage_options_update(array(
            'kind' => 'energy',
            'kickback' => array(5, 0, 0),
            'success' => array(1, 0, -10, 10, 'The '.$this_ability->print_name().' splashed into the target!'),
            'failure' => array(1, -30, -10, -10, 'The '.$this_ability->print_name().' missed&hellip;')
            ));
        $this_ability->recovery_options_update(array(
            'kind' => 'energy',
            'frame' => 'taunt',
            'kickback' => array(5, 0, 0),
            'success' => array(1, 0, -10, 10, 'The '.$this_ability->print_name().' was absorbed by the target!'),
            'failure' => array(1, -30, -10, -10, 'The '.$this_ability->print_name().' had no effect&hellip;')
            ));
        $energy_damage_amount = $this_ability->ability_damage;
        $target_robot->trigger_damage($this_robot, $this_ability, $energy_damage_amount);

        // Attach the ability to the target if not disabled
        if ($target_robot->robot_status != 'disabled'
            && $this_ability->ability_results['this_result'] != 'failure'){

            // If the ability flag was not set, attach the hazard to the target position
            if (!isset($this_battle->battle_attachments[$static_attachment_key][$this_attachment_token])){

                // Attach this ability attachment to the robot using it
                $this_battle->battle_attachments[$static_attachment_key][$this_attachment_token] = $this_attachment_info;
                $this_battle->update_session();

                // Target this robot's self
                $this_robot->robot_frame = 'base';
                $this_robot->update_session();
                $this_ability->target_options_update($this_attachment_info['attachment_create']);
                $target_robot->trigger_target($target_robot, $this_ability);

            }
            // Else if the ability flag was set, reinforce the hazard by one more duration point
            else {

                // Collect the attachment from the robot to back up its info
                $this_attachment_info = $this_battle->battle_attachments[$static_attachment_key][$this_attachment_token];
                $this_attachment_info['attachment_duration'] = $static_attachment_duration;
                $this_battle->battle_attachments[$static_attachment_key][$this_attachment_token] = $this_attachment_info;
                $this_battle->update_session();

                // Target the opposing robot
                $this_ability->target_options_update(array(
                    'frame' => 'defend',
                    'success' => array(9, 85, -10, -10,
                        $this_robot->print_name().' refreshed the '.$this_ability->print_name().'\'s puddle!<br /> '.
                        $target_robot->print_name().'\'s position on the field is still vulnerable to Flame and Explode!'
                        )
                    ));
                $target_robot->trigger_target($target_robot, $this_ability);

            }

        }

        // Either way, update this ability's settings to prevent recovery
        $this_ability->damage_options_update($this_attachment_info['attachment_destroy'], true);
        $this_ability->recovery_options_update($this_attachment_info['attachment_destroy'], true);
        $this_ability->update_session();

        // Return true on success
        return true;


        }
    );
?>