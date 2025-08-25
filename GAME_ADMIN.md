# Game Administration Commands

This document describes the available commands for managing the Revolve game.

## Available Commands

### 1. `game:admin` - Main Administration Command

The main command for all game administration tasks.

#### Usage:
```bash
php artisan game:admin <action> [options]
```

#### Actions:

**`status`** - Show current game status
```bash
php artisan game:admin status
```
Shows:
- Current turn, season, and game state
- Total users and governments
- Top 5 players by score

**`list-users`** - List all registered users
```bash
php artisan game:admin list-users
```
Shows a table with:
- User ID, name, and email
- Government name
- Population, score, and money

**`reset-all`** - Reset all players' games
```bash
php artisan game:admin reset-all [--confirm]
```
- Deletes all government data, resources, infrastructure, and policies
- Creates fresh governments with default values for all users
- Use `--confirm` to skip confirmation prompt

**`reset-user`** - Reset specific user's game
```bash
php artisan game:admin reset-user --user=email@example.com [--confirm]
```
- Resets only the specified user's game
- Requires `--user` option with email address
- Use `--confirm` to skip confirmation prompt

**`reset-state`** - Reset game state only
```bash
php artisan game:admin reset-state [--confirm]
```
- Resets turn counter to 1
- Resets season to spring
- Resets turn state to planning
- Keeps all player data intact

### 2. `game:reset` - Direct Reset Command

Direct command for resetting player games.

#### Usage:
```bash
php artisan game:reset [--user=email] [--confirm]
```

- `--user=email` - Reset specific user by email
- `--confirm` - Skip confirmation prompt
- No options = Reset all users

### 3. `game:reset-state` - Direct State Reset Command

Direct command for resetting game state.

#### Usage:
```bash
php artisan game:reset-state [--confirm]
```

- `--confirm` - Skip confirmation prompt

## Examples

### Check current game status:
```bash
php artisan game:admin status
```

### List all users:
```bash
php artisan game:admin list-users
```

### Reset all players (with confirmation):
```bash
php artisan game:admin reset-all
```

### Reset specific user:
```bash
php artisan game:admin reset-user --user=player@example.com
```

### Reset game state only:
```bash
php artisan game:admin reset-state
```

### Reset all players without confirmation:
```bash
php artisan game:admin reset-all --confirm
```

## What Gets Reset

### Full Player Reset (`reset-all`, `reset-user`):
- ✅ Government data (population, money, sectors)
- ✅ All infrastructure (buildings, levels, assigned population)
- ✅ All resources (food, electricity, medicine, etc.)
- ✅ All policies (active and inactive)
- ✅ Creates new government with default values
- ✅ Assigns random starting resources (50-200 each)

### Game State Reset (`reset-state`):
- ✅ Turn counter → 1
- ✅ Season → spring
- ✅ Turn state → planning
- ✅ Keeps all player data intact

## Safety Features

- **Confirmation Prompts**: All destructive operations require confirmation
- **Database Transactions**: All operations use transactions for data integrity
- **Error Handling**: Comprehensive error handling with rollback
- **Progress Indicators**: Shows progress for bulk operations
- **Detailed Logging**: Shows what was deleted and created

## Default Values After Reset

When a player's game is reset, they get:

- **Population**: 100 (from config)
- **Money**: $1,000 (from config)
- **Sectors**: All at level 1
- **Overall Score**: 4.0
- **Resources**: Random amounts (50-200 each)
- **Infrastructure**: None (must be built)
- **Policies**: None (must be enacted)
