<?
// TORNADO MAN
$robot = array(
    'robot_number' => 'DLN-066',
    'robot_game' => 'MM09',
    'robot_name' => 'Tornado Man',
    'robot_token' => 'tornado-man',
    'robot_image_editor' => 3842,
    'robot_core' => 'wind',
    'robot_description' => 'Weather Control Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('electric', 'freeze'),
    'robot_immunities' => array('wind'),
    'robot_abilities' => array(
        'tornado-blow',
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
                array('level' => 0, 'token' => 'tornado-blow')
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