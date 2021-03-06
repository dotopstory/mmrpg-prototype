<?
// PIRATE MAN
$robot = array(
    'robot_number' => 'KGN-004',
    'robot_game' => 'MM085',
    'robot_name' => 'Pirate Man',
    'robot_token' => 'pirate-man',
    'robot_core' => 'explode',
    'robot_description' => 'Scurvy Sea-Dog Robot',
    'robot_energy' => 100,
    'robot_attack' => 100,
    'robot_defense' => 100,
    'robot_speed' => 100,
    'robot_weaknesses' => array('flame', 'electric'),
    'robot_resistances' => array('water', 'earth'),
    'robot_abilities' => array(
        'remote-mine',
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
                array('level' => 0, 'token' => 'remote-mine')
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