<?
// MAGMA MAN
$robot = array(
    'robot_number' => 'DLN-071',
    'robot_game' => 'MM09',
    'robot_name' => 'Magma Man',
    'robot_token' => 'magma-man',
    'robot_image_editor' => 3842,
    'robot_image_size' => 80,
    'robot_core' => 'flame',
    'robot_description' => 'Geothermal Power Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('wind', 'freeze'),
    'robot_resistances' => array('water'),
    'robot_immunities' => array('flame'),
    'robot_abilities' => array(
        'magma-bazooka',
        'buster-shot',
        'attack-boost', 'attack-break', 'attack-swap', 'attack-mode',
        'defense-boost', 'defense-break', 'defense-swap', 'defense-mode',
        'speed-boost', 'speed-break', 'speed-swap', 'speed-mode',
        'energy-boost', 'energy-break', 'energy-swap', 'energy-mode',
        'field-support', 'mecha-support',
        'light-buster', 'wily-buster', 'cossack-buster'
        ),
    'robot_rewards' => array(
        'abilities' => array(
                array('level' => 0, 'token' => 'magma-bazooka')
            )
        ),
    'robot_quotes' => array(
        'battle_start' => '',
        'battle_taunt' => '',
        'battle_victory' => '',
        'battle_defeat' => ''
        )
    );
?>