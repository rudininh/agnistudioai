# Opportunity Score

## Overview

The Opportunity Score is Agni Studio AI's "PageRank" - a single metric (0-100) that quantifies the potential value of a content idea for a specific creator. It helps creators prioritize where to invest their limited time and energy by predicting which ideas are most likely to succeed based on multiple factors.

## Scoring Formula

`
Opportunity Score = 
  (Trend Strength × 0.25) +
  (Audience Relevance × 0.20) +
  (Competitive Gap × 0.20) +
  (Seasonal Timing × 0.15) +
  (Content Format Fit × 0.10) +
  (Historical Performance Prediction × 0.10)
`



## Component Breakdown

### 1. Trend Strength (0-25 points)
Measures how "hot" a topic is right now based on:
- Search volume growth (week-over-week, month-over-month)
- Social media mention velocity
- Related keyword trend acceleration
- News/reference volume increase

Measurement:
- 0-5: Declining or stagnant interest
- 6-10: Mild interest, slow growth
- 11-15: Steady growth
- 16-20: Strong upward trend
- 21-25: Explosive/viral growth

### 2. Audience Relevance (0-20 points)
How well the topic matches YOUR specific audience's interests:
- Historical engagement on similar topics
- Audience survey/prediction data
- Lookalike audience behavior
- Content affinity modeling

Measurement:
- 0-4: Poor match (audience historically uninterested)
- 5-8: Below average interest
- 9-12: Average/resonant topics
- 13-16: High interest topics
- 17-20: Core passion topics (consistently high engagement)

### 3. Competitive Gap (0-20 points)
Identifies underserved opportunities in your niche:
- Competitor content volume vs. search/social demand
- Quality gap analysis (are existing answers inadequate?)
- Format opportunity (are competitors missing certain formats?)
- Depth/gaps in current coverage

Measurement:
- 0-4: Oversaturated topic (too much competition)
- 5-8: Competitive but opportunities exist
- 9-12: Moderate opportunity (some gaps)
- 13-16: Significant underserved demand
- 17-20: Wide open opportunity (high demand, low supply)

### 4. Seasonal Timing (0-15 points)
How timely is this topic given upcoming events:
- Calendar events (holidays, seasons, anniversaries)
- Industry events (conferences, product launches, award shows)
- Trending cycles (back-to-school, tax season, New Year resolutions)
- Viral potential timing

Measurement:
- 0-3: Poor timing (off-season, irrelevant timing)
- 4-6: Acceptable timing (neutral timing)
- 7-9: Good timing (upcoming relevance)
- 10-12: Excellent timing (peak relevance window)
- 13-15: Critical timing (happening now or imminent)

### 5. Content Format Fit (0-10 points)
How suitable is this topic for YOUR preferred content style:
- Matches your typical production complexity
- Aligns with your aesthetic/budget constraints
- Fits your typical episode length
- Works with your available talent/skills
- Suits your editorial approach

Measurement:
- 0-2: Poor fit (requires capabilities you don't have)
- 3-4: Below average fit (significant adaptation needed)
- 5-6: Average fit (reasonable adaptation required)
- 7-8: Good fit (minor adjustments needed)
- 9-10: Excellent fit (natural extension of your style)

### 6. Historical Performance Prediction (0-10 points)
Based on your past content, how should this perform:
- Performance of topically similar videos
- Format-specific historical averages
- Audience retention patterns for similar topics
- Engagement rate correlations with past content
- Any available conversion/success metrics

Measurement:
- 0-2: Poor historical performance (similar content underperformed)
- 3-4: Below average historical performance
- 5-6: Average historical performance
- 7-8: Above average historical performance
- 9-10: Excellent historical performance (similar content excelled)

## Score Interpretation & Action Guidelines

### 85-100: ?? EXCELLENT OPPORTUNITY (Priority: HIGH)
- Strong data-backed recommendation
- Expected to significantly outperform channel average
- Consider allocating premium production resources
- Ideal for flagship/content pillar pieces
- Action: Produce within recommended time window

### 70-84: ?? GOOD OPPORTUNITY (Priority: MEDIUM-HIGH)
- Solid recommendation with good upside
- Likely to perform at or above channel average
- Suitable for regular content schedule
- Action: Include in upcoming content batch

### 55-69: ?? MODERATE OPPORTUNITY (Priority: MEDIUM)
- Mixed signals requiring careful consideration
- Success highly dependent on execution quality
- Consider as experimental or test content
- Action: Produce only if passionate or as controlled experiment

### 40-54: ?? LOW OPPORTUNITY (Priority: LOW-MEDIUM)
- Weak recommendation with notable limitations
- Significant risk of underperforming
- Only consider if aligned with passion/brand building
- May serve as filler content or community-building
- Action: Produce sparingly, manage expectations

### 0-39: ?? AVOID (Priority: LOW)
- Poor predicted performance probability
- High risk of wasted creative effort
- Consider only for skill building or experimentation
- Look for ways to reframe or improve scoring elements
- Action: Defer or substantially rework concept

## Display Recommendations

### Primary Display
- Large circular gauge (0-100) with color coding
- Numerical score prominent in center
- Descriptive label (EXCELLENT, GOOD, etc.) below score
- Small trend arrow showing recent score movement

### Detailed View (On Click/Hover)
- Bar chart showing each component's contribution
- Raw data sources for each score
- Confidence indicator based on data quality/completeness
- Similar historical examples and their outcomes
- Suggested adjustments to improve score

## Implementation Considerations

### Dynamic Weighting
Advanced users can adjust category weights based on current goals:
- Growth phase: Increase Trend Strength and Competitive Gap weights
- Engagement phase: Increase Audience Relevance and Historical Performance weights
- Experimental phase: Equal weights or emphasize Novelty factors

### Cold Start Strategy
For new channels (<10 videos):
- Use niche/category averages for Historical Performance
- Lean more heavily on Trend and Audience data
- Increase confidence intervals until sufficient personal data exists

### Temporal Components
- All scores recalculate every 6 hours for time-sensitive opportunities
- Seasonal ?????????? update daily as dates approach
- Trend components update based on data refresh intervals (typically hourly)
- Manual "refresh" option available for immediate re-evaluation

### Confidence Scoring
Each Opportunity Score includes a confidence indicator:
- **High:** Multiple strong data sources, sufficient historical data
- **Medium:** Some data limitations or conflicting signals
- **Low:** Limited data, high uncertainty, or new/unproven area

This confidence level should influence how strongly creators weigh the score in their decision-making process.

## Example Application

Tech reviewer considering "iPhone 15 Pro Max Camera Test":

- Trend Strength: 22/25 (launch week, massive search volume)
- Audience Relevance: 18/20 (audience loves camera comparisons)
- Competitive Gap: 14/20 (many reviews but few deep dives)
- Seasonal Timing: 15/15 (launch week = peak timing)
- Content Format Fit: 9/10 (matches their detailed review style)
- Historical Prediction: 8/10 (similar reviews performed 20% above average)

Total: 86/100 ? ?? EXCELLENT OPPORTUNITY, High Confidence

