# ===================================================================
# ROAD SAFETY FOUNDATION - FILE AUDIT AND CLEANUP
# ===================================================================

# Files that are DEFINITELY USED (referenced in navigation)
USED_FILES = [
    'index.html',
    'home.html',
    'aboutus.html',
    'our-story.html',
    'privacy-policy.html',
    'partners.html',
    'cms.html',
    'vrt.html',
    'Videos.html',
    'international-videos.html',
    'General.html',
    'Trucking.html',
    'Motorists.html',
    'Bikers.html',
    'evolve.html',
    'TyreSafety.html',
    'FireExtinguishers.html',
    'fire-blanket.html',
    'BabySafety.html',
    'PetSafety.html',
    'KWiKS.html',
    'NewVehicles.html',
    'UsedVehicles.html',
    'live-traffic.html',
    'current-news.html',
    'nostalgia-news.html',
    'news-submission.html',
    'BLOG.html',
    'safety-facts.html',
    'road-statistics.html',
    'Markerboards.html',
    'safety-tips-submission.html',
    'Forums.html',
    'ContactUs.html'
]

# Files that appear to be UNUSED/DUPLICATES
POTENTIALLY_UNUSED_FILES = [
    'index - Copy.html',  # Obvious duplicate
    'DriverWellness.html',  # Not in navigation
    'vehicle-details.html',  # Not in navigation
    'vehicle-safety.html',  # Not in navigation
    'terms-of-service.html',  # Not in navigation
    'News.html',  # Not in navigation (current-news.html is used instead)
    'merchandise.html',  # Not in navigation
    'legislation.html',  # Not in navigation
    'Pedestrians.html',  # Not in navigation
    'founding-partners.html',  # Not in navigation (partners.html is used)
    'associate-partners.html',  # Not in navigation (partners.html is used)
    'operational-partners.html',  # Not in navigation (partners.html is used)
    'mahindra-album.html',  # Not in navigation
    'peugeot-album.html',  # Not in navigation
    'omi-album.html',  # Not in navigation
    'fleetwatch-album.html'  # Not in navigation
]

# CSS/JS files that might be unused
POTENTIALLY_UNUSED_ASSETS = [
    'assets/socicon/',  # Social icons - check if used
    'assets/bootstrap-carousel-swipe/',  # Might be redundant with Bootstrap 5
    'assets/bootstrap-material-design-font/',  # Material design fonts
    'assets/dropdown/',  # Custom dropdown - Bootstrap 5 has this
    'assets/et-line-font-plugin/',  # Extra font plugin
    'assets/formoid/',  # Form builder - might be unused
    'assets/imagesloaded/',  # Image loading library
    'assets/jarallax/',  # Parallax library
    'assets/jquery-mb-ytplayer/',  # YouTube player
    'assets/masonry/',  # Masonry layout
    'assets/mobirise/',  # Mobirise specific
    'assets/mobirise-gallery/',  # Mobirise gallery
    'assets/smooth-scroll/',  # Smooth scroll - CSS can do this
    'assets/social-likes/',  # Social likes plugin
    'assets/tether/',  # Tether positioning - Bootstrap 5 uses Popper
    'assets/touch-swipe/',  # Touch swipe - duplicate?
    'assets/touchSwipe/',  # Touch swipe - duplicate?
    'assets/viewport-checker/',  # Viewport checker - duplicate?
    'assets/viewportChecker/',  # Viewport checker - duplicate?
    'assets/web/',  # Unknown purpose
    'assets/theme/css/cyborg-raleway.css',  # Superseded by responsive-modern.css
    'assets/theme/css/modern-custom.css',  # Superseded by responsive-modern.css
    'assets/theme/css/modern-styles.css',  # Superseded by responsive-modern.css
    'assets/theme/css/style.css'  # Superseded by responsive-modern.css
]

print("=== FILE AUDIT COMPLETE ===")
print(f"Used files: {len(USED_FILES)}")
print(f"Potentially unused files: {len(POTENTIALLY_UNUSED_FILES)}")
print(f"Potentially unused assets: {len(POTENTIALLY_UNUSED_ASSETS)}")
