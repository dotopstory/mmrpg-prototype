<?
if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

/*
 * PLAYER MISSION SELECT
 * Only re-generate missions if it is approriate to do
 * so at this time (the player is requesting missions)
 */

// Only generate out mission markup data if conditions allow or do not exist
if (!defined('MMRPG_SCRIPT_REQUEST') ||
  ($this_data_select == 'this_battle_token' && in_array('this_player_token='.$this_prototype_data['this_player_token'], $this_data_condition))){
  if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  // -- STARTER BATTLE : CHAPTER ONE -- //

  // Update the prototype data's global current chapter variable
  $this_prototype_data['this_current_chapter'] = 'one';

  // If the player has completed at least zero battles, display the starter battle
  if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['one'])){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // EVENT MESSAGE : CHAPTER ONE
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = array(
      'option_type' => 'message',
      'option_chapter' => $this_prototype_data['this_current_chapter'],
      'option_maintext' => 'Chapter One : An Unexpected Attack'
      );

    // Generate the battle option with the starter data
    $temp_session_token = $this_prototype_data['this_player_token'].'_battle_'.$this_prototype_data['this_current_chapter'];
    if (empty($_SESSION['PROTOTYPE_TEMP'][$temp_session_token])){
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_battle_omega = mmrpg_prototype_mission_starter($this_prototype_data, 'met', $chapters_levels_common['one'], $this_prototype_data['this_support_robot'], 'intro-field', 1, 'mecha');
      $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];
      mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
      $_SESSION['PROTOTYPE_TEMP'][$temp_session_token] = $temp_battle_omega['battle_token'];
    } else {
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_battle_token = $_SESSION['PROTOTYPE_TEMP'][$temp_session_token];
      $temp_battle_omega = mmrpg_battle::get_index_info($temp_battle_token);
    }

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Add the omega battle to the options, index, and session
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = $temp_battle_omega;

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  }


  // If the player has completed at least one battles, display the home laboratory/castle/citadel battle
  if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['one-2'])){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Generate the battle option with the starter data
    $temp_session_token = $this_prototype_data['this_player_token'].'_battle_'.$this_prototype_data['this_current_chapter'].'-2';
    if (empty($_SESSION['PROTOTYPE_TEMP'][$temp_session_token])){
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_battle_omega = mmrpg_prototype_mission_starter($this_prototype_data, 'sniper-joe', ($chapters_levels_common['one-2']), $this_prototype_data['this_support_robot'], $this_prototype_data['this_player_field'], 1, 'mecha');
      $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];
      mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
      $_SESSION['PROTOTYPE_TEMP'][$temp_session_token] = $temp_battle_omega['battle_token'];
    } else {
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_battle_token = $_SESSION['PROTOTYPE_TEMP'][$temp_session_token];
      $temp_battle_omega = mmrpg_battle::get_index_info($temp_battle_token);
    }

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Add the omega battle to the options, index, and session
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = $temp_battle_omega;

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  }


  // If the player has completed at least one battles, display the trill in attack/defense/speed form battle
  if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['one-3'])){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Generate the battle option with the starter data
    $temp_session_token = $this_prototype_data['this_player_token'].'_battle_'.$this_prototype_data['this_current_chapter'].'-3';
    if (empty($_SESSION['PROTOTYPE_TEMP'][$temp_session_token])){
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_battle_omega = mmrpg_prototype_mission_starter($this_prototype_data, 'trill', ($chapters_levels_common['one-3']), '', 'prototype-subspace', 1, 'boss');
      $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];
      mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
      $_SESSION['PROTOTYPE_TEMP'][$temp_session_token] = $temp_battle_omega['battle_token'];
    } else {
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_battle_token = $_SESSION['PROTOTYPE_TEMP'][$temp_session_token];
      $temp_battle_omega = mmrpg_battle::get_index_info($temp_battle_token);
    }

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Add the omega battle to the options, index, and session
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = $temp_battle_omega;

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  }






  // -- ROBOT MASTER BATTLES : CHAPTER TWO -- //

  // Update the prototype data's global current chapter variable
  $this_prototype_data['this_current_chapter'] = 'two';

  // Only continue if the player has unlocked the required chapters
  if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['two'])){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // EVENT MESSAGE : CHAPTER TWO
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = array(
      'option_type' => 'message',
      'option_chapter' => $this_prototype_data['this_current_chapter'],
      'option_maintext' => 'Chapter Two : Robot Master Revival'
      );

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Increment the phase counter
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_phase'] += 1;
    $this_prototype_data['phase_token'] = 'phase'.$this_prototype_data['battle_phase'];
    $this_prototype_data['phase_battle_token'] = $this_prototype_data['this_player_token'].'-'.$this_prototype_data['phase_token'];

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Populate the battle options with the initial eight robots
    if (isset($this_prototype_data['target_robot_omega'][1][0])){ $this_prototype_data['target_robot_omega'] = $this_prototype_data['target_robot_omega'][1]; }
    //die('<pre>'.print_r($this_prototype_data['target_robot_omega'], true).'</pre>');
    foreach ($this_prototype_data['target_robot_omega'] AS $key => $info){
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

      // Generate the battle option with the starter data
      $temp_session_token = $this_prototype_data['this_player_token'].'_battle_'.$this_prototype_data['this_current_chapter'].'_'.$key;
      if (empty($_SESSION['PROTOTYPE_TEMP'][$temp_session_token])){
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
        $temp_battle_omega = mmrpg_prototype_mission_single($this_prototype_data, $info['robot'], $info['field'], $chapters_levels_common['two']);
        $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];
        mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
        $_SESSION['PROTOTYPE_TEMP'][$temp_session_token] = $temp_battle_omega['battle_token'];
      } else {
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
        $temp_battle_token = $_SESSION['PROTOTYPE_TEMP'][$temp_session_token];
        $temp_battle_omega = mmrpg_battle::get_index_info($temp_battle_token);
      }

      // Add the omega battle to the options, index, and session
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $this_prototype_data['battle_options'][] = $temp_battle_omega;

    }

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  }


  // -- NEW CHALLENGER BATTLE : CHAPTER THREE -- //

  // Update the prototype data's global current chapter variable
  $this_prototype_data['this_current_chapter'] = 'three';

  // If the first 1 + 8 battles are complete, unlock the ninth and recollect markup
  if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['three'])){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // EVENT MESSAGE : CHAPTER THREE
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = array(
      'option_type' => 'message',
      'option_chapter' => $this_prototype_data['this_current_chapter'],
      'option_maintext' => 'Chapter Three : The Rival Challengers'
      );

    // Unlock the rival fortress battle (fortress-i)
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $temp_index_token = $this_prototype_data['this_player_token'].'-fortress-i';
    $temp_battle_token = $this_prototype_data['this_player_token'].'-phase'.$this_prototype_data['battle_phase'].'-fortress-i';
    $temp_battle_omega = mmrpg_prototype_mission_fortress($this_prototype_data, $chapters_levels_common['three'], $temp_index_token, $temp_battle_token);

    // Add the omega battle to the battle options
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = $temp_battle_omega;
    mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
    //exit('$temp_battle_omega = <pre>'.print_r($temp_battle_omega, true).'</pre>');

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  }


  // -- FUSION FIELD BATTLES : CHAPTER FOUR -- //

  // Update the prototype data's global current chapter variable
  $this_prototype_data['this_current_chapter'] = 'four';

  // Only continue if the player has unlocked the required chapters
  if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['four'])){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // EVENT MESSAGE : CHAPTER FOUR
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = array(
    'option_type' => 'message',
    'option_chapter' => $this_prototype_data['this_current_chapter'],
    'option_maintext' => 'Chapter Four : Battle Field Fusions'
    );

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Increment the phase counter
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_phase'] += 1;
    $this_prototype_data['phase_token'] = 'phase'.$this_prototype_data['battle_phase'];
    $this_prototype_data['phase_battle_token'] = $this_prototype_data['this_player_token'].'-'.$this_prototype_data['phase_token'];

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Populate the battle options with the initial eight robots combined
    if (isset($this_prototype_data['target_robot_omega'][1][0])){ $this_prototype_data['target_robot_omega'] = $this_prototype_data['target_robot_omega'][1]; }
    foreach ($this_prototype_data['target_robot_omega'] AS $key => $info){
      // Generate the second info option and skip if already used
      if ($key > 0 && ($key + 1) % 2 == 0){ continue; }
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

      // Generate the battle option with the starter data
      $temp_session_token = $this_prototype_data['this_player_token'].'_battle_'.$this_prototype_data['this_current_chapter'].'_'.$key;
      if (empty($_SESSION['PROTOTYPE_TEMP'][$temp_session_token])){
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
        $info2 = $this_prototype_data['target_robot_omega'][$key + 1];
        $temp_battle_omega = mmrpg_prototype_mission_double($this_prototype_data, array($info['robot'], $info2['robot']), array($info['field'], $info2['field']), $chapters_levels_common['four'], true, true);
        $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];
        mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
        $_SESSION['PROTOTYPE_TEMP'][$temp_session_token] = $temp_battle_omega['battle_token'];
      } else {
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
        $temp_battle_token = $_SESSION['PROTOTYPE_TEMP'][$temp_session_token];
        $temp_battle_omega = mmrpg_battle::get_index_info($temp_battle_token);
      }

      // Add the omega battle to the options, index, and session
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $this_prototype_data['battle_options'][] = $temp_battle_omega;

    }

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  }


  // -- THE FINAL BATTLES : CHAPTER FIVE -- //

  // Update the prototype data's global current chapter variable
  $this_prototype_data['this_current_chapter'] = 'five';

  // Only continue if the player has unlocked the required chapters
  if ($this_prototype_data['prototype_complete']
    || !empty($this_prototype_data['this_chapter_unlocked']['five'])
    || !empty($this_prototype_data['this_chapter_unlocked']['five-2'])
    || !empty($this_prototype_data['this_chapter_unlocked']['five-3'])){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // EVENT MESSAGE : CHAPTER FOUR
    $this_prototype_data['battle_options'][] = array(
      'option_type' => 'message',
      'option_chapter' => $this_prototype_data['this_current_chapter'],
      'option_maintext' => 'Chapter Five : The Final Battles'
      );

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Final Destination I
    // Only continue if the player has unlocked the required chapters
    if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['five'])){
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

      /*
      // Unlock the first of the final destination battles
      $temp_final_option = array('battle_phase' => $this_prototype_data['battle_phase'], 'battle_token' => $this_prototype_data['this_player_token'].'-fortress-ii', 'battle_level' => $chapters_levels_common['five'], 'battle_turns' => (3 * MMRPG_SETTINGS_BATTLETURNS_PERROBOT));
      $temp_final_option['option_chapter'] = $this_prototype_data['this_current_chapter'];
      $this_prototype_data['battle_options'][] = $temp_final_option;
      */

      // Unlock the first of the final destination battles (fortress-ii)
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_index_token = $this_prototype_data['this_player_token'].'-fortress-ii';
      $temp_battle_token = $this_prototype_data['this_player_token'].'-phase'.$this_prototype_data['battle_phase'].'-fortress-ii';
      $temp_battle_omega = mmrpg_prototype_mission_fortress($this_prototype_data, $chapters_levels_common['five'], $temp_index_token, $temp_battle_token);

      // Add the omega battle to the battle options
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $this_prototype_data['battle_options'][] = $temp_battle_omega;
      mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
      //exit('$temp_battle_omega = <pre>'.print_r($temp_battle_omega, true).'</pre>');

      // DEBUG DEBUG DEBUG
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    }

    // Final Destination II
    // Only continue if the player has unlocked the required chapters
    if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['five-2'])){
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

      /*
      // Unlock the second of the final destination battles
      $temp_final_option = array('battle_phase' => $this_prototype_data['battle_phase'], 'battle_token' => $this_prototype_data['this_player_token'].'-fortress-iii', 'battle_level' => $chapters_levels_common['five']);
      $temp_final_option['option_chapter'] = $this_prototype_data['this_current_chapter'];
      $this_prototype_data['battle_options'][] = $temp_final_option;
      */

      // Unlock the second of the final destination battles (fortress-iii)
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_index_token = $this_prototype_data['this_player_token'].'-fortress-iii';
      $temp_battle_token = $this_prototype_data['this_player_token'].'-phase'.$this_prototype_data['battle_phase'].'-fortress-iii';
      $temp_battle_omega = mmrpg_prototype_mission_fortress($this_prototype_data, $chapters_levels_common['five-2'], $temp_index_token, $temp_battle_token);

      // Add the omega battle to the battle options
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $this_prototype_data['battle_options'][] = $temp_battle_omega;
      mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
      //exit('$temp_battle_omega = <pre>'.print_r($temp_battle_omega, true).'</pre>');

      // DEBUG DEBUG DEBUG
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    }

    // Final Destination III
    // Only continue if the player has unlocked the required chapters
    if ($this_prototype_data['prototype_complete'] || !empty($this_prototype_data['this_chapter_unlocked']['five-3'])){
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

      // Collect the robot index for quick use
      $temp_robots_index = mmrpg_robot::get_index(); //$DB->get_array_list("SELECT * FROM mmrpg_index_robots WHERE robot_flag_complete = 1;", 'robot_token');
      $temp_fields_index = mmrpg_field::get_index();

      // Collect and define the robot masters and support mechas to appear on this field
      $temp_robot_masters = array();
      $temp_support_mechas = array();
      if (isset($this_prototype_data['target_robot_omega'][1][0])){ $this_prototype_data['target_robot_omega'] = $this_prototype_data['target_robot_omega'][1]; }
      foreach ($this_prototype_data['target_robot_omega'] AS $key => $info){
        $temp_field_info = mmrpg_field::parse_index_info($temp_fields_index[$info['field']]);
        if (!empty($temp_field_info['field_master'])){ $temp_robot_masters[] = $temp_field_info['field_master']; }
        if (!empty($temp_field_info['field_mechas'])){ $temp_support_mechas[] = array_pop($temp_field_info['field_mechas']); }
      }

      // Add the masters info into the omega battle
      $temp_robot_masters_tokens = $temp_robot_masters;
      $temp_robot_masters = array();
      foreach ($temp_robot_masters_tokens AS $key => $token){
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
        $index = mmrpg_robot::parse_index_info($temp_robots_index[$token]);
        $info = array();
        $info['robot_id'] = (MMRPG_SETTINGS_TARGET_PLAYERID + $key + 1);
        $info['robot_token'] = $token;
        $info['robot_level'] = $chapters_levels_common['five-3'];
        $info['robot_abilities'] = array();
        $info['robot_abilities'] = mmrpg_prototype_generate_abilities($index, $info['robot_level'], 8);
        $temp_robot_masters[] = $info;
      }
      shuffle($temp_robot_masters);

      // Unlock the first of the final destination battles (fortress-iv)
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_index_token = $this_prototype_data['this_player_token'].'-fortress-iv';
      $temp_battle_token = $this_prototype_data['this_player_token'].'-phase'.$this_prototype_data['battle_phase'].'-fortress-iv';
      $temp_battle_omega = mmrpg_prototype_mission_fortress($this_prototype_data, $chapters_levels_common['five-3'], $temp_index_token, $temp_battle_token, $temp_robot_masters, $temp_support_mechas);

      // Add the omega battle to the battle options
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $this_prototype_data['battle_options'][] = $temp_battle_omega;
      mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
      //exit('$temp_battle_omega = <pre>'.print_r($temp_battle_omega, true).'</pre>');

      // DEBUG DEBUG DEBUG
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    }

  }


  // -- PROTOTYPE COMPLETE BATTLE : BONUS BATTLES -- //

  // Update the prototype data's global current chapter variable
  $this_prototype_data['this_current_chapter'] = 'bonus';

  // Only continue if the player has unlocked the required chapters
  if ($this_prototype_data['prototype_complete'] || $this_prototype_data['this_chapter_unlocked']['bonus']){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // EVENT MESSAGE : BONUS BATTLES
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = array(
      'option_type' => 'message',
      'option_chapter' => $this_prototype_data['this_current_chapter'],
      'option_maintext' => 'Bonus Battles : Prototype Complete!'
      );

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Generate the bonus battle and using the prototype data
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $temp_battle_omega = mmrpg_prototype_mission_bonus($this_prototype_data, 3, 'mecha');
    $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];
    // Add the omega battle to the options, index, and session
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = $temp_battle_omega;
    mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

    // Generate the bonus battle and using the prototype data
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $temp_battle_omega = mmrpg_prototype_mission_bonus($this_prototype_data, 6, 'master');
    $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];
    // Add the omega battle to the options, index, and session
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    $this_prototype_data['battle_options'][] = $temp_battle_omega;
    mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);

    // DEBUG DEBUG DEBUG
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

  }

  // -- SPECIAL PLAYER BATTLE : PLAYER BATTLES -- //

  // Update the prototype data's global current chapter variable
  $this_prototype_data['this_current_chapter'] = 'player';

  // Unlock a battle with a randomized player from the leaderboards if the game is done
  //$temp_flags = !empty($_SESSION['GAME']['flags']) ? $_SESSION['GAME']['flags'] : array();
  $temp_ptoken = str_replace('-', '', $this_prototype_data['this_player_token']);
  if ($this_prototype_data['prototype_complete'] || $this_prototype_data['this_chapter_unlocked']['player']){
    if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
    //die('checkpoint1');
    if (true){
      //die('checkpoint2');

      // EVENT MESSAGE : PLAYER BATTLES
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $this_prototype_data['battle_options'][] = array(
        'option_type' => 'message',
        'option_chapter' => $this_prototype_data['this_current_chapter'],
        'option_maintext' => 'Player Battles : Leaderboard Challengers'
        );

      // DEBUG DEBUG DEBUG
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

      // Include the leaderboard data for pruning
      $this_leaderboard_online_players = mmrpg_prototype_leaderboard_online();
      $temp_include_usernames = array();
      if (!empty($this_leaderboard_online_players)){
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
        foreach ($this_leaderboard_online_players AS $info){ $temp_include_usernames[] = $info['token']; }
      }

      // Pull a random set of players from the database with similar point levels
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      $temp_player_list = mmrpg_prototype_leaderboard_targets($this_userid, 9, $this_prototype_data['target_player_token'], $this_prototype_data['this_player_token']);
      if (empty($temp_player_list)){ $temp_player_list = mmrpg_prototype_leaderboard_targets($this_userid, 9, $this_prototype_data['this_player_token'], $this_prototype_data['this_player_token']); }

      // DEBUG DEBUG DEBUG
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

      // If player data was actuall pulled, continue
      if (!empty($temp_player_list)){
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

        // Shuffle the player list
        $max_battle_count = 2;
        if ($temp_player_list >= 4){ $max_battle_count = 4; }
        if ($temp_player_list >= 6){ $max_battle_count = 6; }
        //uasort($temp_player_list, 'mmrpg_prototype_leaderboard_targets_sort');
        $temp_player_list = array_slice($temp_player_list, 0, 6);
        shuffle($temp_player_list);

        // Loop through the list up for two to four times, creating new battles
        if (empty($_SESSION['PROTOTYPE_TEMP'][$this_prototype_data['this_player_token'].'_player_battle_factors'])){
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          $temp_field_factors_one = $this_omega_factors_two;
          $temp_field_factors_two = $this_omega_factors_one;
          $temp_field_factors_three = $this_omega_factors_three;
          shuffle($temp_field_factors_one);
          shuffle($temp_field_factors_two);
          shuffle($temp_field_factors_three);
          $temp_one = array_merge($temp_field_factors_one, $temp_field_factors_two, $temp_field_factors_three);
          $temp_two = array_merge($temp_field_factors_one, $temp_field_factors_two, $temp_field_factors_three);
          $temp_three = array_merge($temp_field_factors_one, $temp_field_factors_two, $temp_field_factors_three);
          $temp_field_factors_one = $temp_one;
          $temp_field_factors_two = $temp_two;
          $temp_field_factors_three = $temp_three;
          shuffle($temp_field_factors_one);
          shuffle($temp_field_factors_two);
          shuffle($temp_field_factors_three);
          $_SESSION['PROTOTYPE_TEMP'][$this_prototype_data['this_player_token'].'_player_battle_factors'] = array($temp_field_factors_one, $temp_field_factors_two, $temp_field_factors_three);
        } else {
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          list($temp_field_factors_one, $temp_field_factors_two, $temp_field_factors_three) = $_SESSION['PROTOTYPE_TEMP'][$this_prototype_data['this_player_token'].'_player_battle_factors'];
        }

        // DEBUG DEBUG DEBUG
        if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

        for ($i = 0; $i < $max_battle_count; $i++){
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }

          // DEBUG
          //echo('<pre>$temp_field_factors_one:'.print_r($temp_field_factors_one, true).'</pre><hr />');
          //echo('<pre>$temp_field_factors_two:'.print_r($temp_field_factors_two, true).'</pre><hr />');
          //echo('<pre>$temp_field_factors_three:'.print_r($temp_field_factors_three, true).'</pre><hr />');
          //die();

          // If there are no more players, break
          if (empty($temp_player_list)){ break; }
          //die('<pre>$temp_player_list : '.print_r($temp_player_list, true).'</pre>');

          // Pull and random player from the list and collect their full data
          $temp_max_robots = 2;
          if ($i >= 2 && $this_prototype_data['robots_unlocked'] >= 4){ $temp_max_robots = 4; }
          if ($i >= 4 && $this_prototype_data['robots_unlocked'] >= 8){ $temp_max_robots = 8; }
          //$temp_max_robots = 8; // MAYBE?
          //$temp_player_data = array_shift($temp_player_list); //$temp_player_list[array_rand($temp_player_list)];
          $temp_player_array = array_shift($temp_player_list);
          $temp_battle_omega = mmrpg_prototype_mission_player($this_prototype_data, $temp_player_array, $temp_max_robots, $temp_field_factors_one, $temp_field_factors_two, $temp_field_factors_three);

          // DEBUG DEBUG DEBUG
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          //die('<pre>$temp_battle_omega1 : '.print_r($temp_battle_omega, true).'</pre>');

          // If the collected omega battle was empty, continue gracefully
          if (empty($temp_battle_omega) || empty($temp_battle_omega['battle_token'])){
            //$i--;
            //die('<pre>$temp_battle_omega1.5 : '.print_r($temp_battle_omega, true).'</pre>');
            continue;
          }
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          //die('<pre>$temp_battle_omega2 : '.print_r($temp_battle_omega, true).'</pre>');

          // DEBUG DEBUG DEBUG
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          //die('<pre>$temp_battle_omega3 : '.print_r($temp_battle_omega, true).'</pre>');

          // DEBUG
          //echo('<pre>$temp_field_factors_one:'.print_r($temp_field_factors_one, true).'</pre><hr />');

          // If there was no battle token defined, we have a problem
          //if (empty($temp_battle_omega['battle_token'])){ die('<pre>$temp_battle_omega:'.print_r($temp_battle_omega, true).'</pre>'); }

          // Update the option chapter to the current
          $temp_battle_omega['option_chapter'] = $this_prototype_data['this_current_chapter'];

          // Define the button name if not set already
          $temp_battle_omega['battle_button'] = !empty($temp_battle_omega['battle_button']) ? $temp_battle_omega['battle_button'] : $temp_battle_omega['battle_name'];

          //die('<pre>$temp_player_array : '.print_r($temp_player_array, true).'</pre>');

          // If this user is online, update the battle button with details
          if (!empty($temp_player_array['values']['flag_online'])){
            if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
            $temp_battle_omega['option_style'] = 'border-color: green !important; ';
            $temp_battle_omega['battle_button'] .= ' <sup class="online_type player_type player_type_nature">Online</sup>';
          }

          // If this user is custom, update the battle button with details
          if (!empty($temp_player_array['values']['flag_custom'])){
            if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
            $temp_battle_omega['battle_button'] .= ' <sup class="custom_type player_type player_type_flame">&hearts;</sup>';
          }

          // DEBUG DEBUG DEBUG
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          //die('<pre>$temp_battle_omega4 : '.print_r($temp_battle_omega, true).'</pre>');

          // Add the omega battle to the options, index, and session
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          $this_prototype_data['battle_options'][] = $temp_battle_omega;
          mmrpg_battle::update_index_info($temp_battle_omega['battle_token'], $temp_battle_omega);
          unset($temp_battle_omega);

          // DEBUG DEBUG DEBUG
          if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
          //die('<pre>$temp_battle_omega5 : ---</pre>');

        }

      }

      // Unset the temp player array
      if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
      //die('<pre>checkpoint 6 i guess? : ---</pre>');
      unset($temp_player_list);

    }

  }


}
if (MMRPG_CONFIG_DEBUG_MODE){ mmrpg_debug_checkpoint(__FILE__, __LINE__);  }
//die('<pre>checkpoint 7 i guess? : ---</pre>');



?>