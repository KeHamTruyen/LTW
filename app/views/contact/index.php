<!-- Contact Page -->
<section class="contact-section" style="padding: 4rem 0; background: #f8f9fa;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #c3e6cb;">
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #f5c6cb;">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div style="text-align: center; margin-bottom: 3rem;">
            <h1 style="font-size: 2.5rem; font-weight: 700; color: #333; margin-bottom: 1rem;">Liên Hệ Với Chúng Tôi</h1>
            <p style="font-size: 1.1rem; color: #666; max-width: 600px; margin: 0 auto;">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy điền form bên dưới hoặc liên hệ trực tiếp qua thông tin bên cạnh.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            
            <!-- Contact Form -->
            <div style="background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: #333;">Gửi Tin Nhắn</h2>
                
                <form action="<?= BASE_URL ?>contact/submit" method="POST" id="contactForm">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                            Tên của bạn <span style="color: red;">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required 
                            minlength="2"
                            value="<?= htmlspecialchars($_SESSION['form_data']['name'] ?? '') ?>"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#667eea'"
                            onblur="this.style.borderColor='#ddd'"
                        >
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                            Email <span style="color: red;">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#667eea'"
                            onblur="this.style.borderColor='#ddd'"
                        >
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                            Số điện thoại
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone"
                            value="<?= htmlspecialchars($_SESSION['form_data']['phone'] ?? '') ?>"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#667eea'"
                            onblur="this.style.borderColor='#ddd'"
                        >
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="subject" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                            Chủ đề
                        </label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject"
                            value="<?= htmlspecialchars($_SESSION['form_data']['subject'] ?? '') ?>"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#667eea'"
                            onblur="this.style.borderColor='#ddd'"
                        >
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                            Tin nhắn <span style="color: red;">*</span>
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            required 
                            minlength="10"
                            rows="6"
                            style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; font-family: inherit; resize: vertical; transition: border-color 0.3s;"
                            onfocus="this.style.borderColor='#667eea'"
                            onblur="this.style.borderColor='#ddd'"
                        ><?= htmlspecialchars($_SESSION['form_data']['message'] ?? '') ?></textarea>
                    </div>

                    <?php unset($_SESSION['form_data']); ?>

                    <button 
                        type="submit" 
                        style="width: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; border: none; border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(102,126,234,0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                    >
                        Gửi Tin Nhắn
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div>
                <div style="background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; color: #333;">Thông Tin Liên Hệ</h2>
                    
                    <div style="margin-bottom: 2rem;">
                        <div style="display: flex; align-items: start; margin-bottom: 1.5rem;">
                            <div style="background: #f0f4ff; padding: 1rem; border-radius: 10px; margin-right: 1rem; flex-shrink: 0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 10C21 17 12 23 12 23C12 23 3 17 3 10C3 7.61305 3.94821 5.32387 5.63604 3.63604C7.32387 1.94821 9.61305 1 12 1C14.3869 1 16.6761 1.94821 18.364 3.63604C20.0518 5.32387 21 7.61305 21 10Z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="10" r="3" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem; color: #333;">Địa chỉ</h3>
                                <p style="color: #666; line-height: 1.6;">
                                    Khu phố 6, Phường Linh Trung<br>
                                    Thủ Đức, TP.HCM
                                </p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: start; margin-bottom: 1.5rem;">
                            <div style="background: #f0f4ff; padding: 1rem; border-radius: 10px; margin-right: 1rem; flex-shrink: 0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 16.92V19.92C22.0016 20.1985 21.9441 20.4741 21.832 20.7293C21.72 20.9845 21.5559 21.2136 21.3502 21.4018C21.1446 21.5901 20.902 21.7335 20.6377 21.8227C20.3735 21.9119 20.0935 21.945 19.8157 21.92C16.7428 21.5856 13.7912 20.5341 11.1975 18.85C8.78659 17.3146 6.74315 15.2661 5.21249 12.85C3.52622 10.2412 2.48043 7.27097 2.15749 4.17997C2.13251 3.90344 2.16538 3.62474 2.254 3.3616C2.34263 3.09846 2.48506 2.85666 2.67226 2.6516C2.85945 2.44653 3.0873 2.28268 3.34128 2.1705C3.59527 2.05831 3.86983 2.00024 4.14749 1.99997H7.14749C7.6328 1.9952 8.10328 2.16705 8.47125 2.48351C8.83922 2.79996 9.07957 3.23942 9.14749 3.71997C9.27411 4.68004 9.50894 5.6227 9.84749 6.52997C9.98204 6.8879 10.0112 7.27689 9.9314 7.65086C9.85164 8.02482 9.66635 8.36809 9.39749 8.63998L8.12749 9.90997C9.55105 12.4135 11.6239 14.4864 14.1275 15.91L15.3975 14.64C15.6694 14.3711 16.0126 14.1858 16.3866 14.1061C16.7606 14.0263 17.1496 14.0554 17.5075 14.19C18.4148 14.5285 19.3574 14.7634 20.3175 14.89C20.8033 14.9585 21.2469 15.2032 21.564 15.5775C21.8812 15.9518 22.0097 16.4296 21.9975 16.92Z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem; color: #333;">Điện thoại</h3>
                                <p style="color: #666; line-height: 1.6;">
                                    <a href="tel:+0398715611" style="color: #667eea; text-decoration: none;">+039 871-5611</a>
                                </p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: start;">
                            <div style="background: #f0f4ff; padding: 1rem; border-radius: 10px; margin-right: 1rem; flex-shrink: 0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M22 6L12 13L2 6" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="font-weight: 600; margin-bottom: 0.5rem; color: #333;">Email</h3>
                                <p style="color: #666; line-height: 1.6;">
                                    <a href="mailto:petschoice@outlook.com" style="color: #667eea; text-decoration: none;">petschoice@outlook.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; border-radius: 12px; color: white;">
                    <h3 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 1rem;">Giờ Làm Việc</h3>
                    <div style="line-height: 2;">
                        <p><strong>Thứ 2 - Thứ 6:</strong> 8:00 - 18:00</p>
                        <p><strong>Thứ 7:</strong> 9:00 - 17:00</p>
                        <p><strong>Chủ nhật:</strong> Nghỉ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Client-side validation
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();
    const phone = document.getElementById('phone').value.trim();
    
    let errors = [];
    
    if (name.length < 2) {
        errors.push('Tên phải có ít nhất 2 ký tự');
    }
    
    if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
        errors.push('Email không hợp lệ');
    }
    
    if (message.length < 10) {
        errors.push('Tin nhắn phải có ít nhất 10 ký tự');
    }
    
    if (phone && !phone.match(/^[0-9+\-\s()]+$/)) {
        errors.push('Số điện thoại không hợp lệ');
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Vui lòng sửa các lỗi sau:\n' + errors.join('\n'));
        return false;
    }
});
</script>

<style>
@media (max-width: 768px) {
    .contact-section > div > div {
        grid-template-columns: 1fr !important;
    }
}
</style>

