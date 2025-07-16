# Cyborg Theme Implementation Guide
## Road Safety Foundation Website

### Overview
All pages have been updated to use the **Bootswatch Cyborg theme** with **Raleway typography** for a consistent, modern dark theme with cyan accents.

### Key Features Applied:
- **Dark background** (#060606) with cyan (#2a9fd6) and electric blue accents
- **Raleway font family** throughout the entire website
- **Consistent navigation** with static horizontal menu
- **Modern card designs** with proper shadows and borders
- **Responsive design** that works on all devices

### CSS Files Used:
1. **Bootswatch Cyborg Bootstrap**: `https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/cyborg/bootstrap.min.css`
2. **Custom Cyborg-Raleway CSS**: `assets/theme/css/cyborg-raleway.css`

### Color Palette:
- **Primary**: #2a9fd6 (Cyan)
- **Secondary**: #77b300 (Green)
- **Success**: #77b300 (Green)
- **Info**: #9933cc (Purple)
- **Warning**: #ff8800 (Orange)
- **Danger**: #cc0000 (Red)
- **Background**: #060606 (Dark)
- **Cards/Secondary**: #222 (Dark Gray)
- **Borders**: #333 (Medium Gray)
- **Text**: #fff (White)
- **Muted Text**: #adafae (Light Gray)

### Pages Updated:
✅ **index.html** - Complete Cyborg theme with modern hero section
✅ **Videos.html** - Cyborg theme with video cards and YouTube integration
✅ **aboutus.html** - Head section updated with Cyborg theme
✅ **cyborg-raleway.css** - Comprehensive CSS file for all components

### YouTube Channel:
🎥 **Road Matters Channel**: https://www.youtube.com/@RoadMatters-r8b
- All YouTube links updated to point to the correct channel
- Subscribe buttons and social media links updated

### Pages Still Need Updates:
- ContactUs.html
- DriverWellness.html
- Forums.html
- News.html
- Pedestrians.html
- PetSafety.html
- Trucking.html
- BLOG.html
- legislation.html
- Markerboards.html

### Standard Head Section Template:
```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="assets/images/rsfsa-140x128-1.png" type="image/x-icon">
  <meta name="description" content="[Page Description]">
  <title>[Page Title] - Road Safety Foundation</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;200;300;400;500;600;700;800&family=Lato:wght@100;300;400;700&display=swap" rel="stylesheet">

  <!-- Core Stylesheets -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/cyborg/bootstrap.min.css">
  <link rel="stylesheet" href="assets/theme/css/cyborg-raleway.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/animate.css/animate.min.css">
</head>
```

### Standard Navigation Template:
```html
<nav class="modern-nav fixed-top" id="mainNav">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between w-100">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="assets/images/rsfsa-140x128-1.png" alt="Road Safety Foundation" height="40" class="me-2">
                <span class="fw-bold text-info">Road Safety Foundation</span>
            </a>

            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item">
                    <a class="nav-link" href="aboutus.html">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Videos.html">Videos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="legislation.html">Legislation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="PetSafety.html">Motor Vehicles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="DriverWellness.html">Driver Wellness</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Pedestrians.html">Pedestrians</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Trucking.html">Trucking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.html#partners">Partners</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="News.html">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Forums.html">Forums</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="BLOG.html">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ContactUs.html">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
```

### Key Bootstrap Classes to Use:
- **Backgrounds**: `bg-dark`, `bg-secondary`
- **Text Colors**: `text-light`, `text-info`, `text-success`, `text-warning`
- **Buttons**: `btn-info`, `btn-success`, `btn-outline-info`
- **Cards**: `card bg-dark border-info`
- **Badges**: `badge text-bg-info`

### Typography Classes:
- **Headings**: `display-1` to `display-6` with `fw-light`
- **Body Text**: `lead` for larger text, `text-light` for body
- **Emphasis**: `text-info` for highlights, `text-success` for positive

### Implementation Notes:
1. All pages should have `padding-top: 80px` on body for fixed navigation
2. Use consistent spacing with Bootstrap's spacing utilities
3. Maintain the dark theme throughout with proper contrast
4. Ensure all interactive elements have hover states
5. Keep the Raleway font family consistent across all elements

### Testing Checklist:
- [ ] Navigation works on all screen sizes
- [ ] All text is readable with proper contrast
- [ ] Hover effects work on interactive elements
- [ ] Images load properly with dark theme
- [ ] Forms (if any) follow the dark theme
- [ ] Responsive design works on mobile devices
