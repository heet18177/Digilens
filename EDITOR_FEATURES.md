# Rich Text Editor Features

## Overview
The blog now uses **Quill.js** as the rich text editor, providing a modern, free alternative to TinyMCE and CKEditor. Quill.js is lightweight, customizable, and doesn't require any API keys.

## Features

### Text Formatting
- **Headers**: H1-H6 support
- **Text Styles**: Bold, italic, underline, strikethrough
- **Colors**: Text color and background color
- **Alignment**: Left, center, right, justify
- **Lists**: Ordered and unordered lists with indentation
- **Blockquotes**: For highlighting important content
- **Code Blocks**: For code snippets

### Media Support
- **Images**: Upload and embed images directly in the editor
- **Links**: Add hyperlinks to external content
- **Videos**: Embed video content (URL-based)

### User Experience
- **Dark Mode**: Automatically adapts to the user's theme preference
- **Responsive**: Works well on desktop and mobile devices
- **Auto-save**: Content is automatically saved to the hidden textarea
- **Validation**: Prevents submission of empty content
- **File Upload**: Secure image upload with validation

### Technical Features
- **No API Keys Required**: Completely free to use
- **Lightweight**: Smaller bundle size compared to other editors
- **Customizable**: Easy to modify toolbar and functionality
- **Modern**: Built with modern web standards
- **Accessible**: Good keyboard navigation and screen reader support

## File Upload Security

### Image Upload Restrictions
- **File Types**: Only JPEG, PNG, GIF, and WebP images are allowed
- **File Size**: Maximum 5MB per image
- **Path Security**: Images are stored in the `uploads/blog-images/` directory
- **Authentication**: Only authenticated users can upload images
- **CSRF Protection**: All uploads are protected with CSRF tokens

### Upload Process
1. User clicks the image button in the toolbar
2. File picker opens with image type filter
3. File is validated for type and size
4. Image is uploaded to the server via AJAX
5. Server returns the image URL
6. Image is embedded in the editor

## Customization

### Toolbar Configuration
The editor toolbar can be customized by modifying the `quillConfig` object in `/public/js/quill-editor.js`:

```javascript
modules: {
    toolbar: [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        // Add or remove toolbar items here
    ]
}
```

### Dark Mode Styling
Dark mode styles are automatically applied when the user's theme is set to dark. The styles can be customized in the `applyDarkMode()` method.

## Browser Support
- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 79+

## Performance
- **Bundle Size**: ~45KB gzipped (Quill.js core)
- **Load Time**: Fast initialization and rendering
- **Memory Usage**: Efficient memory management
- **Mobile Performance**: Optimized for touch devices

## Migration from TinyMCE
The migration from TinyMCE to Quill.js provides:
- Better performance
- No API key requirements
- More modern interface
- Better mobile support
- Easier customization
- Smaller bundle size

## Troubleshooting

### Common Issues
1. **Editor not loading**: Ensure Quill.js CDN is accessible
2. **Image upload failing**: Check file permissions on uploads directory
3. **Dark mode not working**: Verify theme detection logic
4. **Content not saving**: Check form submission and textarea sync

### Debug Mode
Enable debug mode by opening browser console and checking for JavaScript errors. The editor logs helpful information for troubleshooting.
