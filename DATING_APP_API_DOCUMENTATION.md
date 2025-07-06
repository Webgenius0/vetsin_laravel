# Dating App API Documentation

## Overview
This document describes the new dating app features added to the Laravel API. The app combines dating functionality with real estate preferences to help users find compatible matches.

## New User Fields

### Basic Dating Profile Fields
- `date_of_birth` (date): User's date of birth
- `location` (string): User's current location
- `relationship_goal` (enum): 'casual', 'serious', 'friendship', 'marriage'
- `preferred_age_min` (integer): Minimum preferred age (18-100)
- `preferred_age_max` (integer): Maximum preferred age (18-100)

### Real Estate Preferences
- `preferred_property_type` (enum): 'apartment', 'house', 'condo', 'townhouse', 'studio', 'any'
- `identity` (enum): 'buyer', 'seller', 'renter', 'investor'
- `budget_min` (decimal): Minimum budget amount
- `budget_max` (decimal): Maximum budget amount
- `preferred_location` (string): Preferred real estate location

### Personal Questions
- `perfect_weekend` (text): "My perfect weekend looks..."
- `cant_live_without` (text): "One thing I can't live without in a home is..."
- `quirky_fact` (text): "A quirky fact about me that always surprises people..."
- `about_me` (text): "Write about yourself"
- `tags` (json): Array of tags that suit the user

## API Endpoints

### 1. Complete Basic Profile
**POST** `/api/profile/basic`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "date_of_birth": "1990-05-15",
    "location": "New York, NY",
    "relationship_goal": "serious",
    "preferred_age_min": 25,
    "preferred_age_max": 35
}
```

**Response:**
```json
{
    "success": true,
    "message": "Basic profile completed successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "date_of_birth": "1990-05-15",
        "location": "New York, NY",
        "relationship_goal": "serious",
        "preferred_age_min": 25,
        "preferred_age_max": 35,
        "age": 33,
        "is_profile_complete": true,
        "is_real_estate_complete": false
    },
    "code": 200
}
```

### 2. Complete Real Estate Preferences
**POST** `/api/profile/real-estate`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "preferred_property_type": "apartment",
    "identity": "buyer",
    "budget_min": 200000,
    "budget_max": 500000,
    "preferred_location": "Manhattan, NY"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Real estate preferences completed successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "preferred_property_type": "apartment",
        "identity": "buyer",
        "budget_min": "200000.00",
        "budget_max": "500000.00",
        "preferred_location": "Manhattan, NY",
        "is_profile_complete": true,
        "is_real_estate_complete": true
    },
    "code": 200
}
```

### 3. Complete Personal Questions
**POST** `/api/profile/personal-questions`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "perfect_weekend": "Exploring new restaurants and hiking in the mountains",
    "cant_live_without": "A cozy reading nook with natural light",
    "quirky_fact": "I can recite the entire alphabet backwards",
    "about_me": "I'm a passionate foodie who loves to travel and explore new cultures. I enjoy cooking, hiking, and reading mystery novels.",
    "tags": ["foodie", "traveler", "adventurous", "bookworm"]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Personal questions completed successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "perfect_weekend": "Exploring new restaurants and hiking in the mountains",
        "cant_live_without": "A cozy reading nook with natural light",
        "quirky_fact": "I can recite the entire alphabet backwards",
        "about_me": "I'm a passionate foodie who loves to travel and explore new cultures. I enjoy cooking, hiking, and reading mystery novels.",
        "tags": ["foodie", "traveler", "adventurous", "bookworm"]
    },
    "code": 200
}
```

### 4. Get Profile Status
**GET** `/api/profile/status`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Profile status retrieved successfully",
    "data": {
        "basic_profile_complete": true,
        "real_estate_complete": true,
        "personal_questions_complete": true,
        "overall_complete": true
    },
    "code": 200
}
```

### 5. Update Tags
**POST** `/api/profile/tags`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "tags": ["foodie", "traveler", "adventurous", "bookworm", "fitness"]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Tags updated successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "tags": ["foodie", "traveler", "adventurous", "bookworm", "fitness"]
    },
    "code": 200
}
```

### 6. Updated User Data Endpoint
**GET** `/api/users/data`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "User data fetched successfully",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "uploads/User/Avatar/1234567890.jpg",
        "date_of_birth": "1990-05-15",
        "location": "New York, NY",
        "relationship_goal": "serious",
        "preferred_age_min": 25,
        "preferred_age_max": 35,
        "preferred_property_type": "apartment",
        "identity": "buyer",
        "budget_min": "200000.00",
        "budget_max": "500000.00",
        "preferred_location": "Manhattan, NY",
        "perfect_weekend": "Exploring new restaurants and hiking in the mountains",
        "cant_live_without": "A cozy reading nook with natural light",
        "quirky_fact": "I can recite the entire alphabet backwards",
        "about_me": "I'm a passionate foodie who loves to travel and explore new cultures. I enjoy cooking, hiking, and reading mystery novels.",
        "tags": ["foodie", "traveler", "adventurous", "bookworm"],
        "age": 33,
        "is_profile_complete": true,
        "is_real_estate_complete": true
    },
    "code": 200
}
```

## Validation Rules

### Basic Profile
- `date_of_birth`: Required, must be a valid date before today
- `location`: Required, max 255 characters
- `relationship_goal`: Required, must be one of: casual, serious, friendship, marriage
- `preferred_age_min`: Required, integer between 18-100
- `preferred_age_max`: Required, integer between 18-100, must be greater than or equal to preferred_age_min

### Real Estate Preferences
- `preferred_property_type`: Required, must be one of: apartment, house, condo, townhouse, studio, any
- `identity`: Required, must be one of: buyer, seller, renter, investor
- `budget_min`: Required, numeric, minimum 0
- `budget_max`: Required, numeric, minimum 0, must be greater than or equal to budget_min
- `preferred_location`: Required, max 255 characters

### Personal Questions
- `perfect_weekend`: Required, max 1000 characters
- `cant_live_without`: Required, max 1000 characters
- `quirky_fact`: Required, max 1000 characters
- `about_me`: Required, max 2000 characters
- `tags`: Optional array, each tag max 50 characters

## Error Responses

### Validation Error (422)
```json
{
    "status": false,
    "message": "Validation Error",
    "data": {
        "date_of_birth": ["The date of birth field is required."],
        "preferred_age_max": ["The preferred age max must be greater than or equal to preferred age min."]
    },
    "code": 422
}
```

### Unauthorized (401)
```json
{
    "status": false,
    "message": "Unauthorized",
    "data": [],
    "code": 401
}
```

### Server Error (500)
```json
{
    "status": false,
    "message": "Something went wrong",
    "data": [],
    "code": 500
}
```

## Implementation Notes

1. **Age Calculation**: The API automatically calculates user age from date_of_birth
2. **Profile Completion**: The API provides computed attributes to check if different sections are complete
3. **Tags**: Tags are stored as JSON array and can be updated independently
4. **Validation**: All endpoints include comprehensive validation with clear error messages
5. **Authentication**: All profile endpoints require JWT authentication

## Migration

To apply the database changes, run:
```bash
php artisan migrate
```

This will add all the new dating app fields to the users table. 