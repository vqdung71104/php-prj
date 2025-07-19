<!-- templates/footer.php -->
<footer class="site-footer">
    <div class="footer-container">
        <!-- Phần liên hệ và thông tin -->
        <div class="footer-section">
            <h3 class="footer-heading">LIÊN HỆ</h3>
            <!--<div class="footer-links">
                <a href="/gioi-thieu">Giới thiệu</a>
                <a href="/tong-hop">Tổng hợp</a>
            </div>
        </div>-->

        <!-- Phần thông tin pháp lý -->
        <div class="footer-legal">
            <p>Giấy phép thiết lập trang thông tin điện tử tổng hợp số xxx/xx/TTTM do Bộ Thông tin và Truyền thông cấp ngày 05/05/20xx</p>
            <p>Đơn vị chủ quản: Công ty Cổ phần Công nghệ DungVQ - Địa chỉ: số 6, Phúc Lợi, Long Biên, Hà Nội</p>
            <p>Điện thoại: (024) 6969 9696 | Email: contact@dung.com.vn</p>
            <p>Trang thông tin chịu trách nhiệm nội dung và đảm bảo các thông tin tuân thủ quy định pháp luật</p>
        </div>

        <!-- Phần quảng cáo và liên kết ngoài -->
        <div class="footer-external">
            <a href="https://baomoi.com/tag/Pthigones.epl" target="_blank" rel="noopener noreferrer">https://baomoi.com/tag/Pthigones.epl</a>
            <span class="ad-label">Quảng cáo</span>
        </div>
    </div>
</footer>

<style>
    /* CSS cho footer */
    .site-footer {
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 30px 0;
        margin-top: 40px;
        font-size: 14px;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-section {
        margin-bottom: 20px;
    }

    .footer-heading {
        color: #f39c12;
        font-size: 18px;
        margin-bottom: 15px;
        border-bottom: 1px solid #34495e;
        padding-bottom: 5px;
    }

    .footer-links a {
        color: #bdc3c7;
        text-decoration: none;
        display: block;
        margin-bottom: 8px;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: #f39c12;
    }

    .footer-legal {
        border-top: 1px solid #34495e;
        padding-top: 20px;
        margin-top: 20px;
        line-height: 1.6;
    }

    .footer-legal p {
        margin-bottom: 10px;
    }

    .footer-external {
        margin-top: 20px;
        text-align: center;
    }

    .footer-external a {
        color: #3498db;
        text-decoration: none;
    }

    .ad-label {
        display: block;
        color: #95a5a6;
        font-size: 12px;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .footer-container {
            padding: 0 15px;
        }
        
        .footer-heading {
            font-size: 16px;
        }
    }
</style>

</body>
</html>