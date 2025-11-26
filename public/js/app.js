// Dark mode toggle
document.addEventListener('DOMContentLoaded', () => {
    const darkModeToggle = document.getElementById('darkModeToggle');
    
    if (darkModeToggle) {
        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.classList.toggle('dark', currentTheme === 'dark');
        
        darkModeToggle.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            const theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        });
    }
});

// Vote functionality
async function vote(blogId, voteType) {
    try {
        const response = await fetch('/vote', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                blog_id: blogId,
                vote_type: voteType
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update vote counts
            document.getElementById(`upvote-count-${blogId}`).textContent = data.upvotes;
            document.getElementById(`downvote-count-${blogId}`).textContent = data.downvotes;
            
            // Update button states
            const upvoteBtn = document.getElementById(`upvote-btn-${blogId}`);
            const downvoteBtn = document.getElementById(`downvote-btn-${blogId}`);
            
            upvoteBtn.classList.remove('text-primary-600', 'bg-primary-50');
            downvoteBtn.classList.remove('text-red-600', 'bg-red-50');
            
            if (data.userVote === 'upvote') {
                upvoteBtn.classList.add('text-primary-600', 'bg-primary-50');
            } else if (data.userVote === 'downvote') {
                downvoteBtn.classList.add('text-red-600', 'bg-red-50');
            }
        } else {
            if (response.status === 401) {
                window.location.href = '/login';
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Like functionality
async function toggleLike(blogId) {
    try {
        const response = await fetch('/like', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                blog_id: blogId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const likeBtn = document.getElementById(`like-btn-${blogId}`);
            const likeCount = document.getElementById(`like-count-${blogId}`);
            
            likeCount.textContent = data.count;
            
            if (data.liked) {
                likeBtn.classList.add('text-red-600');
                likeBtn.innerHTML = '<i class="fas fa-heart"></i>';
            } else {
                likeBtn.classList.remove('text-red-600');
                likeBtn.innerHTML = '<i class="far fa-heart"></i>';
            }
        } else {
            if (response.status === 401) {
                window.location.href = '/login';
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Bookmark functionality
async function toggleBookmark(blogId) {
    try {
        const response = await fetch('/bookmark', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                blog_id: blogId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const bookmarkBtn = document.getElementById(`bookmark-btn-${blogId}`);
            
            if (data.bookmarked) {
                bookmarkBtn.classList.add('text-yellow-600');
                bookmarkBtn.innerHTML = '<i class="fas fa-bookmark"></i>';
            } else {
                bookmarkBtn.classList.remove('text-yellow-600');
                bookmarkBtn.innerHTML = '<i class="far fa-bookmark"></i>';
            }
            
            // Show toast notification
            showToast(data.message);
        } else {
            if (response.status === 401) {
                window.location.href = '/login';
            } else {
                alert(data.message);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Comment functionality
async function submitComment(blogId, parentId = null) {
    const content = parentId 
        ? document.getElementById(`reply-content-${parentId}`).value
        : document.getElementById('comment-content').value;
    
    if (!content.trim()) {
        alert('Please enter a comment');
        return;
    }
    
    try {
        const response = await fetch('/comment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                blog_id: blogId,
                parent_id: parentId || '',
                content: content
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload page to show new comment
            location.reload();
        } else {
            alert(data.message || 'Failed to post comment');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Delete comment
async function deleteComment(commentId) {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }
    
    try {
        const response = await fetch(`/comment/${commentId}/delete`, {
            method: 'POST'
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Delete blog post
async function deleteBlog(blogId) {
    if (!confirm('Are you sure you want to delete this blog post? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch(`/blog/${blogId}/delete`, {
            method: 'POST'
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } animate-fade-in`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Image preview
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
            document.getElementById(previewId).classList.remove('hidden');
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle reply form
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('hidden');
}

// Auto-dismiss flash messages
document.addEventListener('DOMContentLoaded', () => {
    const flashMessages = document.querySelectorAll('.flash-message');
    
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });
});

