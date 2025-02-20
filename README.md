# Saxon Bulletin WordPress Theme

A modern, feature-rich WordPress theme built for content-focused blogging platforms. Saxon Bulletin combines clean design with powerful functionality to create an engaging user experience.

## Features

### Content Presentation
- Responsive grid and list view layouts
- Featured posts carousel
- Category highlights
- Newsletter integration
- Dark mode support
- Animated post cards
- Social sharing integration

### Performance
- Optimized image loading
- Tailwind CSS for efficient styling
- Lazy loading for images
- Minimal JavaScript footprint
- Progressive enhancement

### User Experience
- Intuitive navigation
- Smooth animations
- Accessible design
- Cross-browser compatibility
- Mobile-first approach

### Content Management
- Custom post submission system
- Featured post management
- Newsletter subscriber management
- Advanced analytics dashboard
- Category organization

## Installation

1. Download the theme
2. Go to WordPress admin → Appearance → Themes
3. Click "Add New" then "Upload Theme"
4. Upload the theme ZIP file
5. Click "Install Now"
6. Activate the theme

## Configuration

### Theme Options
Navigate to Appearance → Customize to configure:
- Site identity and logo
- Color schemes
- Typography options
- Layout preferences
- Social media links
- Footer content

### Featured Posts
To mark a post as featured:
1. Edit the post
2. Look for the "Featured Post" checkbox in the sidebar
3. Check the box and update the post

### Newsletter Setup
1. Go to Saxon → Newsletter Settings
2. Configure email templates
3. Set up subscriber preferences
4. Customize form appearance

## Development

### Prerequisites
- Node.js (v14 or higher)
- npm or yarn
- WordPress 5.9+
- PHP 7.4+

### Local Development
```bash
# Clone the repository
git clone https://github.com/your-repo/saxon-bulletin

# Install dependencies
npm install

# Start development server
npm run dev

# Build for production
npm run build
```

### File Structure
```
saxon-bulletin/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── components/
│   ├── header/
│   ├── post/
│   └── shared/
├── inc/
│   ├── customizer/
│   ├── newsletter/
│   └── template-functions.php
├── templates/
│   └── newsletter/
├── functions.php
├── style.css
└── README.md
```

### Customization
The theme uses Tailwind CSS for styling. To customize:
1. Modify `tailwind.config.js`
2. Update CSS in `assets/css/`
3. Run `npm run build` to compile

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This theme is licensed under the GPL-2.0 License - see the [LICENSE](LICENSE) file for details.

## Credits

- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [PostCSS](https://postcss.org/)
- Icon sets from [Heroicons](https://heroicons.com/)

## Support

For support, feature requests, or bug reports:
- Open an issue on GitHub
- Visit our [support forum](https://example.com/support)
- Email us at support@example.com

## Documentation

Full documentation is available at [docs.example.com](https://docs.example.com)

## Changelog

### 1.0.0 (2024-02-20)
- Initial release
- Featured posts system
- Newsletter integration
- Dark mode support
- Custom post submission