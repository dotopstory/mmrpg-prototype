<?
// ITEM : BLUE SCORE BALL
$ability = array(
  'ability_name' => 'Blue Score Ball',
  'ability_token' => 'item-score-ball-blue',
  'ability_game' => 'MM00',
  'ability_group' => 'MMRPG/Items/Points',
  'ability_class' => 'item',
  'ability_subclass' => 'consumable',
  'ability_type' => '',
  'ability_type2' => 'water',
  'ability_description' => 'A small blue sphere that glows with a mysterious power. Using this item in battle grants the player an addition {RECOVERY} battle points at the end of the current mission, but only of they are victorious.',
  'ability_energy' => 0,
  'ability_speed' => 10,
  'ability_recovery' => 10000,
  'ability_recovery_percent' => false,
  'ability_accuracy' => 100,
  'ability_function' => function($objects){

    // Extract all objects into the current scope
    extract($objects);

    // Target this robot's self
    $this_ability->target_options_update(array(
      'frame' => 'summon',
      'success' => array(0, 60, -2, 99,
        $this_player->print_name().' uses an item from the inventory&hellip; <br />'.
        $this_robot->print_name().' activates the '.$this_ability->print_name().'!'
        )
      ));
    $this_robot->trigger_target($this_robot, $this_ability);

    // Define the star points and turns before boosting
    $temp_start_points = $this_battle->battle_rewards_points;
    $temp_start_turns = $this_battle->battle_turns_limit;

    // Define the reward boost and the goal break
    $temp_reward_boost = $this_ability->ability_recovery;
    $temp_goal_break = $this_ability->ability_damage2;

    // Apply the battle point mods within limits
    $battle_points = $this_battle->battle_rewards_points;
    $battle_points += $temp_reward_boost;
    if ($this_battle->battle_rewards_points > MMRPG_SETTINGS_BATTLEPOINTS_MAXREWARD){ $battle_points = MMRPG_SETTINGS_BATTLEPOINTS_MAXREWARD; }
    elseif ($this_battle->battle_rewards_points < MMRPG_SETTINGS_BATTLEPOINTS_MINREWARD){ $battle_points = MMRPG_SETTINGS_BATTLEPOINTS_MAXREWARD; }
    $this_battle->set_info('battle_rewards_points', $battle_points);

    // Apply the battle turn mods within limits
    $battle_turns_limit = $this_battle->battle_turns_limit;
    $battle_turns_limit -= $temp_goal_break;
    if ($this_battle->battle_turns_limit > MMRPG_SETTINGS_BATTLETURNS_MAXAMOUNT){ $battle_turns_limit = MMRPG_SETTINGS_BATTLETURNS_MAXAMOUNT; }
    elseif ($this_battle->battle_turns_limit < MMRPG_SETTINGS_BATTLETURNS_MINAMOUNT){ $battle_turns_limit = MMRPG_SETTINGS_BATTLETURNS_MAXAMOUNT; }
    $this_battle->set_info('battle_turns_limit', $battle_turns_limit);

    // INCREASE REWARD POINT VALUE
    if (true){

      // Define this ability's attachment token
      $this_attachment_token = 'ability_'.$this_ability->ability_token;
      $this_attachment_info = array(
        'class' => 'ability',
        'ability_id' => $this_ability->ability_id,
        'ability_token' => $this_ability->ability_token,
        'ability_frame' => 1,
        'ability_frame_offset' => array('x' => 0, 'y' => 0, 'z' => -10)
        );

      // Attach this ability attachment to this robot temporarily
      $this_robot->set_frame('taunt');
      $this_robot->set_attachment($this_attachment_token, $this_attachment_info);

      // Attach this ability to all robots on this player's side of the field
      $backup_robots_active = $this_player->values['robots_active'];
      $backup_robots_active_count = !empty($backup_robots_active) ? count($backup_robots_active) : 0;
      if ($backup_robots_active_count > 0){
        // Loop through the this's benched robots, inflicting les and less damage to each
        $this_key = 0;
        foreach ($backup_robots_active AS $key => $info){
          if ($info['robot_id'] == $this_robot->robot_id){ continue; }
          $temp_this_robot = new rpg_robot($this_player, $info);
          // Attach this ability attachment to the this robot temporarily
          $temp_this_robot->set_frame('taunt');
          $temp_this_robot->set_attachment($this_attachment_token, $this_attachment_info);
          unset($temp_this_robot);
          $this_key++;
        }
      }

      // Create the event to show this damage boost
      $random_saying = rpg_functions::get_random_positive_word();
      $this_player->set_frame('victory');
      $this_battle->events_create($this_robot, false, $this_field->field_name.' Rewards',
      	$random_saying.' The mission reward was boosted by <span class="ability_name ability_type ability_type_none">'.number_format($temp_reward_boost, 0, '.', ',').'</span> battle points!<br />'.
        'The base reward value is now at <span class="ability_name ability_type ability_type_none">'.number_format($this_battle->battle_rewards_points, 0, '.', ',').'</span> battle points!'
        );

      // Remove this ability from all robots on this player's side of the field
      $backup_robots_active = $this_player->values['robots_active'];
      $backup_robots_active_count = !empty($backup_robots_active) ? count($backup_robots_active) : 0;
      if ($backup_robots_active_count > 0){
        // Loop through the this's benched robots, inflicting les and less damage to each
        $this_key = 0;
        foreach ($backup_robots_active AS $key => $info){
          if ($info['robot_id'] == $this_robot->robot_id){ continue; }
          $temp_this_robot = new rpg_robot($this_player, $info);
          // Attach this ability attachment to the this robot temporarily
          $temp_this_robot->set_frame('base');
          $temp_this_robot->unset_attachment($this_attachment_token);
          unset($temp_this_robot);
          $this_key++;
        }
      }

      // Remove this ability attachment from this robot
      unset($this_robot->robot_attachments[$this_attachment_token]);
      $this_robot->update_session();

    }

    // Reset player frame
    $this_player->set_frame('base');

    // DECREASE TARGET TURN VALUE
    if (true){

      // Define this ability's attachment token
      $this_attachment_token = 'ability_'.$this_ability->ability_token;
      $this_attachment_info = array(
        'class' => 'ability',
        'ability_id' => $this_ability->ability_id,
        'ability_token' => $this_ability->ability_token,
        'ability_frame' => 2,
        'ability_frame_offset' => array('x' => 0, 'y' => 0, 'z' => -10)
        );

      // Attach this ability attachment to this robot temporarily
      $this_robot->set_frame('defend');
      $this_robot->set_attachment($this_attachment_token, $this_attachment_info);

      // Attach this ability to all robots on this player's side of the field
      $backup_robots_active = $this_player->values['robots_active'];
      $backup_robots_active_count = !empty($backup_robots_active) ? count($backup_robots_active) : 0;
      if ($backup_robots_active_count > 0){
        // Loop through the this's benched robots, inflicting les and less damage to each
        $this_key = 0;
        foreach ($backup_robots_active AS $key => $info){
          if ($info['robot_id'] == $this_robot->robot_id){ continue; }
          $temp_this_robot = new rpg_robot($this_player, $info);
          // Attach this ability attachment to the this robot temporarily
          $temp_this_robot->set_frame('defend');
          $temp_this_robot->set_attachment($this_attachment_token, $this_attachment_info);
          unset($temp_this_robot);
          $this_key++;
        }
      }

      // Create the event to show this damage boost
      $random_saying = rpg_functions::get_random_negative_word();
      $this_player->set_frame('damage');
      $this_battle->events_create($this_robot, false, $this_field->field_name.' Rewards',
      	$random_saying.' The mission target was lowered by <span class="ability_name ability_type ability_type_none">'.number_format($temp_goal_break, 0, '.', ',').'</span> turns!<br />'.
        'The mission target is now at <span class="ability_name ability_type ability_type_none">'.number_format($this_battle->battle_turns_limit, 0, '.', ',').'</span> turns!'
        );

      // Remove this ability from all robots on this player's side of the field
      $backup_robots_active = $this_player->values['robots_active'];
      $backup_robots_active_count = !empty($backup_robots_active) ? count($backup_robots_active) : 0;
      if ($backup_robots_active_count > 0){
        // Loop through the this's benched robots, inflicting les and less damage to each
        $this_key = 0;
        foreach ($backup_robots_active AS $key => $info){
          if ($info['robot_id'] == $this_robot->robot_id){ continue; }
          $temp_this_robot = new rpg_robot($this_player, $info);
          // Attach this ability attachment to the this robot temporarily
          $temp_this_robot->set_frame('base');
          $temp_this_robot->unset_attachment($this_attachment_token);
          unset($temp_this_robot);
          $this_key++;
        }
      }

      // Remove this ability attachment from this robot
      unset($this_robot->robot_attachments[$this_attachment_token]);
      $this_robot->update_session();

    }

    // Reset player frame
    $this_player->set_frame('base');

    // Return true on success
    return true;

  }
  );
?>