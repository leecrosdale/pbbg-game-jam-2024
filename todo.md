# PBBG Game Jam 2024

## Game Name: Revolve

In Revolve, you lead a civilization through the cycles of time, managing resources, population, and policies to build a powerful government. While each player manages their government independently, their actions ripple across a shared world, influencing the global economy, alliances, and conflicts. Engage in trade, war, or offer aid to other governments as you shape not only your own nation’s future but also impact the fate of others. Adapt to natural disasters, political shifts, and the decisions of rival governments in this interconnected, ever-revolving world.


## Idea



1. Seasonal Workforce Allocation
   Cycle: Seasons (Spring, Summer, Fall, Winter)
   Mechanics: Each season offers different resources and challenges. Players must allocate their population to different tasks based on seasonal output (e.g., farming in Spring, resource gathering in Summer, preparing for Winter). Government policies can focus on maximizing production in specific seasons or balancing across all seasons.
   Decision Points: Players could choose how to allocate workers for each season, introduce policies (like farming subsidies or industrialization), and manage infrastructure that improves certain outputs (e.g., irrigation for Spring).
2. Political Ideology Cycles
   Cycle: Shifting political ideologies (e.g., Democracy, Autocracy, Socialism)
   Mechanics: The government undergoes cyclic shifts in political ideologies every few in-game years, which affect policies and how the population responds. Players must adapt their strategies to make the most of each era, maximizing resources based on current government strengths (e.g., Autocracy gives more control, Democracy offers more cooperation from the population).
   Decision Points: Players need to manage unrest during transitions between ideologies and maximize the benefits of the current system.
3. Population Growth and Aging
   Cycle: Generational changes
   Mechanics: As time progresses, your population ages, new people are born, and older generations retire. Players need to manage education, employment, and health care to maximize productivity while balancing an aging population and training the next generation.
   Decision Points: Policies could focus on short-term gains at the expense of future generations or long-term sustainability. Players also manage the effects of population booms and declines.
4. Natural Disasters and Recovery
   Cycle: Disasters (Floods, Droughts, Disease Outbreaks)
   Mechanics: Natural disasters occur periodically, disrupting the economy, agriculture, and infrastructure. Players must prepare their government and population for these events by building resilience and setting aside resources to recover quickly.
   Decision Points: Players decide how much to invest in disaster preparedness versus short-term economic growth. They also manage rebuilding efforts after disasters to restore output.
5. Resource Exhaustion and Renewal
   Cycle: Resource depletion and renewal (Forests, Water, Minerals)
   Mechanics: Resources such as forests, water, and minerals deplete over time with overuse. Players need to implement policies for sustainable resource management, balancing short-term exploitation versus long-term conservation.
   Decision Points: Players can choose whether to extract resources aggressively for short-term gain or focus on sustainability to avoid resource shortages in the future.

# Cycles

## Workforce / Population

```php 

// Example: Workforce allocation (values can be taken from user inputs)
$totalPopulation = 1000;
$agriculture = $totalPopulation * 0.3; // 30% in agriculture
$industry = $totalPopulation * 0.4;    // 40% in industry
$research = $totalPopulation * 0.2;    // 20% in research
$defense = $totalPopulation * 0.1;     // 10% in defense

// Seasonal modifiers
$season = 'Winter'; // dynamically change each cycle
$modifiers = [
    'Spring' => ['agriculture' => 1.25, 'industry' => 0.9],
    'Summer' => ['agriculture' => 1.1, 'industry' => 1.15],
    'Fall' => ['agriculture' => 1.2, 'research' => 1.1],
    'Winter' => ['agriculture' => 0.5, 'industry' => 1.2, 'defense' => 1.15]
];

$agricultureOutput = $agriculture * ($modifiers[$season]['agriculture'] ?? 1);
$industryOutput = $industry * ($modifiers[$season]['industry'] ?? 1);
$researchOutput = $research * ($modifiers[$season]['research'] ?? 1);
$defenseOutput = $defense * ($modifiers[$season]['defense'] ?? 1);

// Calculate total output
$totalOutput = $agricultureOutput + $industryOutput + $researchOutput + $defenseOutput;


```

## Ideology will change

```php 

$ideology = 'Autocracy'; // Changes every X cycles
$ideologyModifiers = [
    'Democracy' => ['morale' => 1.1, 'defense' => 0.95],
    'Autocracy' => ['defense' => 1.15, 'research' => 0.9],
    'Socialism' => ['agriculture' => 1.1, 'research' => 1.05, 'industry' => 0.9]
];

$agricultureOutput *= ($ideologyModifiers[$ideology]['agriculture'] ?? 1);
$industryOutput *= ($ideologyModifiers[$ideology]['industry'] ?? 1);
$researchOutput *= ($ideologyModifiers[$ideology]['research'] ?? 1);
$defenseOutput *= ($ideologyModifiers[$ideology]['defense'] ?? 1);

// Adjust total output based on ideology
$totalOutput = $agricultureOutput + $industryOutput + $researchOutput + $defenseOutput;


```

## Population will age

```php 

$currentPopulation = 1000;
$birthRate = 0.012; // 1.2%
$deathRate = 0.009; // 0.9%
$ageDistribution = [
    'working' => 0.7, // 70% of population is working age
    'retired' => 0.1  // 10% of population is retired
];

// Population growth over one cycle
$newPopulation = $currentPopulation * (1 + $birthRate - $deathRate);

// Adjust workforce based on retirement
$workingPopulation = $newPopulation * $ageDistribution['working'];
$retiredPopulation = $newPopulation * $ageDistribution['retired'];

// Retirement costs (you can define a cost per retired person)
$retirementCosts = $retiredPopulation * 100; // Example cost per person


```

## Natural disasters will happen

```php

// Disaster occurrence
$disasterChance = rand(1, 100); // Randomly generate a number between 1 and 100
$disaster = null;
if ($disasterChance <= 10) {
    $disasters = ['Flood', 'Drought', 'Disease'];
    $disaster = $disasters[array_rand($disasters)];
}

// Disaster effects
$disasterModifiers = [
    'Flood' => ['agriculture' => 0.7, 'defense' => 1.2],
    'Drought' => ['agriculture' => 0.5, 'research' => 1.05],
    'Disease' => ['population' => 0.9, 'workforce' => 0.85]
];

if ($disaster) {
    $agricultureOutput *= ($disasterModifiers[$disaster]['agriculture'] ?? 1);
    $defenseOutput *= ($disasterModifiers[$disaster]['defense'] ?? 1);
    $researchOutput *= ($disasterModifiers[$disaster]['research'] ?? 1);

    // Handle population loss
    $newPopulation *= ($disasterModifiers[$disaster]['population'] ?? 1);
    $workingPopulation *= ($disasterModifiers[$disaster]['workforce'] ?? 1);
}


```


## Resources

```php 

$resources = [
    'forests' => 1000,  // 1000 units of forest
    'water' => 500,     // 500 units of water
    'minerals' => 300   // 300 units of minerals
];

// Usage and renewal
$usage = [
    'forests' => 100,  // 100 units of forest used per cycle
    'water' => 50,
    'minerals' => 30
];

// Deplete resources based on usage
foreach ($resources as $resource => $amount) {
    $resources[$resource] -= $usage[$resource];
}

// Apply renewal rates
$renewalRates = ['forests' => 0.1, 'water' => 0.05, 'minerals' => 0]; // Minerals don't renew
foreach ($resources as $resource => $amount) {
    $resources[$resource] += $amount * $renewalRates[$resource];
}


```

## Balancing Strategies

Balancing Strategies:
Population balancing: Ensure your population grows slowly and that seasonal effects, ideologies, and disasters can both help and hurt growth, creating natural ups and downs.
Resource depletion vs. output: Encourage players to balance short-term resource exploitation with long-term sustainability.
Policy and ideology shifts: Make sure that each political shift or disaster comes with both pros and cons, forcing the player to adapt strategies continuously


## Government Success Score

Success Factors:
Economic Output (EO) – The total production across agriculture, industry, and research.
Population Morale (PM) – The overall happiness of the population, which affects productivity.
Military Strength (MS) – Defense readiness to withstand disasters or unrest.
Sustainability (S) – Effective resource management to avoid collapse.
Policy Effectiveness (PE) – The efficiency and effectiveness of the government’s policies.


## Policies Calculations

| Name                     | Effect Type           | Effect Calculation                             |
|--------------------------|-----------------------|------------------------------------------------|
| Agricultural Reform      | Food Production       | `food_production += (population * 0.1)`      |
| Industry Expansion       | Resource Production   | `materials_production += (population * 0.05)`|
| Scientific Advancement   | Research Points       | `research_points += (allocated_research * 2)`|
| Tax Incentives           | Morale                | `morale += 5`                                 |
| Social Welfare Program   | Morale                | `morale += (population * 0.02)`               |
| Militarization           | Military Strength     | `soldiers += (population * 0.05)`             |
| Environmental Protection | Resource Consumption  | `food_consumption -= (population * 0.01)`     |
| Trade Agreements         | Trade Efficiency      | `trade_efficiency += 10%`                     |
| Mandatory Education      | Research Points       | `research_points += (allocated_industry * 0.1)`|
| Emergency Relief Fund    | Morale                | `morale += 10`                                |

