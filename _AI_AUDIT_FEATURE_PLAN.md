# AI Audit Feature Plan

## Overview

This document outlines the plan for implementing an AI-powered audit feature that analyzes user flows across multiple screens to provide design consistency and UX feedback.

## Why Multi-Screen Audits?

### The Problem with Single-Screen Analysis

-   **Limited Context**: Analyzing one screen in isolation is like proofreading a single page - you can spot typos but miss plot holes
-   **Missed Inconsistencies**: Design inconsistencies (terminology, colors, component styles) only become apparent when comparing multiple screens
-   **No Flow Analysis**: Cannot identify issues in user journey progression or redundant steps

### The Value of Multi-Screen Audits

-   **True Design Auditing**: Elevates the feature from "AI chat on steroids" to genuine design and usability auditing
-   **Strategic Feedback**: Provides higher-order advice on user flows, navigation, and design system consistency
-   **Context-Aware Analysis**: Can identify issues that are impossible to spot when viewing screens individually

## Feasible Implementation: "Flow Audit"

### Why Start with Flow Audits?

-   **Manageable Scope**: 2-7 screens per audit keeps token usage and costs reasonable
-   **High Value**: Captures most of the benefits of multi-screen analysis
-   **Clear Focus**: Concentrates on the most critical multi-screen issue: consistency

### How It Works

#### User Experience

1. **Audit Creation**: User navigates to a dedicated "Audits" page
2. **Flow Selection**: User creates a new audit and selects 2-7 screens representing a specific user flow (e.g., Sign Up → Onboarding → Dashboard)
3. **AI Processing**: System sends structured data from all selected screens to the LLM
4. **Results Display**: User receives a focused report on consistency issues across the flow

#### AI Analysis Focus

The AI will be instructed to focus on consistency across the selected screens:

-   **Terminology**: Are features named consistently? (e.g., "Settings" vs "Preferences")
-   **Color Usage**: Is the primary action color used consistently?
-   **Typography**: Are heading and paragraph styles consistent?
-   **Component Style**: Do buttons, input fields, and other components look and feel the same?

## Technical Implementation

### Backend (Laravel)

#### Database & Models

-   **Audit Model**: Store audit metadata (name, description, created_at, etc.)
-   **AuditScreen Model**: Junction table linking audits to screens with order/sequence
-   **Screen Model Enhancement**: Add method to serialize Figma frame data into structured text

#### API Endpoints

-   `POST /api/audits` - Create new audit
-   `GET /api/audits` - List user's audits
-   `GET /api/audits/{id}` - Get specific audit with results
-   `POST /api/audits/{id}/execute` - Trigger AI analysis
-   `GET /api/audits/{id}/status` - Check processing status

#### Service Layer

-   **PerformFlowAudit Job**: Background job that:
    1. Fetches all screens in the audit
    2. Serializes their Figma data
    3. Sends to AI with focused prompt
    4. Saves structured results
-   **AiAgentService Enhancement**: Updated prompt for multi-screen consistency analysis

### Frontend (Angular)

#### New Components

-   **AuditListComponent**: Display user's audits
-   **AuditCreateComponent**: Create new audit, select screens
-   **AuditReportComponent**: Display AI analysis results
-   **FlowVisualizationComponent**: Show selected screens in sequence

#### Services

-   **AuditService**: Handle all audit-related API calls
-   **ScreenSelectionService**: Manage screen selection for audits

#### State Management

-   Polling mechanism to check audit completion status (5-7 seconds)
-   Real-time updates when audit results are ready

## Example User Flow

### 1. User Action

-   User navigates to "Audits" section in dashboard
-   Clicks "Create New Audit"
-   Names the audit: "User Registration Flow"
-   Selects screens: [Sign Up Form, Email Verification, Welcome Screen, Dashboard]

### 2. AI Processing

The AI receives structured data from all selected screens and analyzes for consistency.

### 3. AI Response Example

```json
{
    "auditId": "audit_123",
    "flowName": "User Registration Flow",
    "overallConsistencyScore": 7.2,
    "issues": [
        {
            "type": "terminology",
            "severity": "medium",
            "description": "Inconsistent button text: 'Create Account' vs 'Sign Up'",
            "screens": ["Sign Up Form", "Email Verification"],
            "suggestion": "Standardize to 'Create Account' across all screens"
        },
        {
            "type": "color_usage",
            "severity": "high",
            "description": "Primary action button color differs between screens",
            "screens": ["Sign Up Form", "Welcome Screen"],
            "suggestion": "Use consistent primary color (#007bff) for all primary actions"
        },
        {
            "type": "component_style",
            "severity": "low",
            "description": "Input field border radius varies (4px vs 8px)",
            "screens": ["Sign Up Form", "Dashboard"],
            "suggestion": "Standardize border radius to 4px across all input fields"
        }
    ],
    "positiveFindings": [
        "Consistent typography hierarchy across all screens",
        "Good use of spacing and padding consistency",
        "Clear visual progression through the flow"
    ]
}
```

### 4. User Experience

-   User sees a clean report highlighting specific inconsistencies
-   Each issue shows which screens are affected
-   Actionable suggestions for improvement
-   Positive reinforcement for good design decisions

## Future Enhancements

### Phase 2: Full Project Audits

-   Analyze entire project for comprehensive design system compliance
-   Advanced flow analysis (identify redundant steps, suggest better UX patterns)
-   Integration with design system documentation

### Phase 3: Advanced Features

-   Automated design system rule enforcement
-   Integration with Figma for automatic fixes
-   Historical tracking of design improvements over time

## Success Metrics

-   **User Adoption**: Number of audits created per month
-   **Issue Resolution**: Percentage of identified issues that get addressed
-   **User Satisfaction**: Feedback on audit quality and usefulness
-   **Design Consistency**: Measurable improvement in design system compliance

## Conclusion

The Flow Audit approach provides immediate value while keeping implementation feasible. It positions the feature as a genuine design auditing tool rather than just an enhanced AI chat, delivering strategic insights that single-screen analysis cannot provide.
