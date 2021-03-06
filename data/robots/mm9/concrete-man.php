<?
// CONCRETE MAN
$robot = array(
    'robot_number' => 'DLN-065',
    'robot_game' => 'MM09',
    'robot_name' => 'Concrete Man',
    'robot_token' => 'concrete-man',
    'robot_image_editor' => 110,
    'robot_image_size' => 80,
    'robot_core' => 'earth',
    'robot_description' => 'Dam Construction Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('water', 'freeze'),
    'robot_resistances' => array('flame', 'wind'),
    'robot_abilities' => array(
        'concrete-shot',
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
                array('level' => 0, 'token' => 'concrete-shot')
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