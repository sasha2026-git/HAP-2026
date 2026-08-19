<?php if (!defined('ABSPATH')) exit;
/**
 * Template Name: 案例与洞察 (杂志版)
 * Description: 杂志排版风格的案例展示与洞察页面
 */

get_header(); ?>

<style>

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--gold:#775a19;--gold-l:#e9c176;--txt:#1a1c1c;--txt-v:#444748;--out-v:#c4c7c7;--bg:#faf9f9;--bg-s:#f4f3f3;--dark:#1b1c19;--fd:'Playfair Display',serif;--fb:'Inter',sans-serif}
body{font-family:var(--fb);background:var(--bg);color:var(--txt);-webkit-font-smoothing:antialiased}

.lang-bar{display:flex;justify-content:flex-end;padding:60px 24px 0;max-width:1200px;margin:0 auto}
.lang-btn{font-family:var(--fb);font-size:12px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;padding:10px 24px;border:1px solid var(--out-v);background:transparent;color:var(--txt-v);cursor:pointer;transition:all .3s}
.lang-btn:first-child{border-radius:24px 0 0 24px}
.lang-btn:last-child{border-radius:0 24px 24px 0;border-left:0}
.lang-btn.on{background:var(--txt);border-color:var(--txt);color:#fff}

.hero{text-align:center;padding:60px 24px 60px;max-width:800px;margin:0 auto;background:#faf9f9;border-radius:0;margin-top:0}
.hero h1{color:#1a1c1c}
.hero p{color:#444748}
.hero span.kicker{font-size:12px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:var(--gold);display:block;margin-bottom:20px}
.hero h1{font-family:var(--fd);font-size:clamp(32px,5vw,56px);font-weight:600;line-height:1.1;margin:0 0 20px}
.hero h1 em{font-style:italic;background:linear-gradient(135deg,#775a19 0%,#fed488 50%,#775a19 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.hero p{font-size:clamp(14px,1.2vw,16px);line-height:1.6;color:var(--txt-v)}

.cases{max-width:1200px;margin:0 auto;padding:0 24px 40px}
.sec-hdr{margin-bottom:40px}
.sec-hdr h2{font-family:var(--fd);font-size:32px;font-weight:600}
.sec-hdr__line{height:4px;width:48px;background:var(--gold);margin-top:10px}

.cases-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:20px;align-items:start}
.case{position:relative;overflow:hidden;border-radius:12px}
.case__media{position:relative;overflow:hidden;border-radius:12px}
.case__img{width:90%;display:block;object-fit:cover;transition:transform .7s;margin:0 auto}
.case:hover .case__img{transform:scale(1.05)}
.case__badge{position:absolute;padding:6px 16px;background:rgba(249,248,243,.7);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px);border:1px solid rgba(119,90,25,.2);border-radius:9999px;font-size:12px;font-weight:600;color:var(--gold);letter-spacing:.05em;white-space:nowrap;z-index:2}
.badge-tr{top:20px;right:20px}
.badge-bl{bottom:20px;left:20px}
.badge-tl{top:20px;left:20px}
.badge-br{bottom:20px;right:20px}
.case__body{padding:20px 0 0}
.case__body h3{font-family:var(--fd);font-size:20px;margin:0 0 6px}
.case__body p{font-size:14px;line-height:1.5;color:var(--txt-v)}

/* ★ 正确的 Grid 列跨度（有空格） */
.case-1{grid-column: span 8}
.case-1 .case__img{aspect-ratio:16/9}
.case-2{grid-column: span 4;margin-top:128px}
.case-2 .case__img{aspect-ratio:3/4}
.case-3{grid-column: span 6}
.case-3 .case__img{aspect-ratio:1/1}
.case-4{grid-column: span 6;margin-top:96px}
.case-4 .case__img{aspect-ratio:4/5}

.pagi{display:flex;justify-content:center;gap:8px;padding:24px 0}
.pagi__dot{width:8px;height:8px;border-radius:50%;border:1px solid var(--out-v);background:transparent;cursor:pointer;padding:0;transition:all .3s}
.pagi__dot.on{background:var(--gold);border-color:var(--gold)}

.insights{background:var(--bg-s);margin:0 -24px;padding:60px 24px}
.insights-hdr{text-align:center;margin-bottom:48px}
.insights-hdr h2{font-family:var(--fd);font-size:32px;margin-bottom:6px}
.insights-hdr p{font-size:12px;font-weight:600;letter-spacing:.3em;text-transform:uppercase;color:var(--txt-v)}
.art-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;max-width:1200px;margin:0 auto}
.art{cursor:pointer}
.art__iw{aspect-ratio:4/5;border-radius:8px;overflow:hidden;margin-bottom:16px}
.art__ph{width:100%;height:100%;background:linear-gradient(135deg,var(--bg),#e8e5df);display:flex;align-items:center;justify-content:center;font-size:48px;color:#747878;transition:transform .7s}
.art:hover .art__ph{transform:scale(1.05)}
.art__cat{font-size:11px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--gold);margin-bottom:8px;display:block}
.art h4{font-family:var(--fd);font-size:18px;line-height:1.3;margin:0 0 8px}
.art h4 em{font-style:italic}
.art:hover h4{color:var(--gold)}
.art p{font-size:13px;line-height:1.5;color:var(--txt-v);margin:0 0 12px}
.art__rt{font-size:11px;font-weight:500;letter-spacing:.1em;text-transform:uppercase;color:var(--txt-v)}

.consult{background:var(--dark);position:relative;overflow:hidden;padding:80px 24px;text-align:center}
.consult__glow{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:600px;height:600px;background:radial-gradient(circle,rgba(119,90,25,.2),transparent 70%);pointer-events:none}
.consult h2{font-family:var(--fd);font-size:clamp(28px,3.5vw,48px);color:#fff;margin:0 0 16px;position:relative}
.consult p{font-size:16px;line-height:1.6;color:rgba(255,255,255,.7);margin:0 0 32px;position:relative}
.consult__btn{display:inline-block;padding:16px 48px;background:linear-gradient(135deg,var(--gold),var(--gold-l));color:#fff;border:none;border-radius:9999px;font-family:var(--fb);font-size:13px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;cursor:pointer;transition:all .3s;position:relative}
.consult__btn:hover{transform:translateY(-2px);box-shadow:0 4px 20px rgba(119,90,25,.4)}

footer{border-top:1px solid var(--out-v);padding:40px 24px;text-align:center;max-width:1200px;margin:0 auto}
footer .links{display:flex;justify-content:center;gap:28px;margin-bottom:20px;flex-wrap:wrap}
footer .links a{font-size:12px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--txt-v);text-decoration:none}
footer .links a:hover{color:var(--gold)}
footer .copy{font-size:13px;color:var(--txt-v)}

/* ★ 移动端：单列 */
@media(max-width:768px){
  .cases-grid{grid-template-columns:1fr}
  .case-1,.case-2,.case-3,.case-4{grid-column:span 1;margin-top:0}
  .case-1 .case__img{aspect-ratio:16/9}
  .case-2 .case__img{aspect-ratio:3/4}
  .case-3 .case__img{aspect-ratio:1/1}
  .case-4 .case__img{aspect-ratio:4/5}
  .art-grid{grid-template-columns:1fr}
  .consult__btn{width:100%;text-align:center}
}

</style>



<div class="lang-bar">
  <button class="lang-btn on" onclick="sw('zh')">CN</button>
  <button class="lang-btn" onclick="sw('en')">EN</button>
</div>

<section class="hero">
  <span class="kicker zh">智慧工坊</span><span class="kicker en" style="display:none">THE ATELIER OF INTELLIGENCE</span>
  <h1><span class="zh">打造数字 <em>人文</em></span><span class="en" style="display:none">Crafting Digital <em>Humanity</em></span></h1>
  <p><span class="zh">技术精度与传承美学的交汇之处。</span><span class="en" style="display:none">Where technical precision meets heritage aesthetic.</span></p>
<div style="width:1px;height:80px;background:linear-gradient(180deg,#775a19,transparent);margin:40px auto 0"></div>
</section>

<section class="cases">
  <div class="sec-hdr">
    <h2><span class="zh">卓越案例</span><span class="en" style="display:none">Collaborative Excellence</span></h2>
    <div class="sec-hdr__line"></div>
  </div>
  <div class="cases-grid">
    <!-- 案例1: 大图 span 8 -->
    <div class="case case-1">
      <div class="case__media">
        <img class="case__img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBKTez5bKYX8hkcGYIQTY7AQolcclgmGZVyeYdO7wtjzKGi1KGDp3W2_rnyulzx-fXVnwh8gbqlgLhL7rQYDO1Hy15ExxYsRLQtHY7utzChF5Rbbt_kkIEIxWTZRt6UaN0wAUNxg3cMa-JBP4U6F5ayebnM_4V0l7fTBRWgVMtAtDjYDzOfIoeVjRBeqtwa1JkujelvLr8_CVdXDxk8Fk5JPJgXAgj5o8PW25SxvcGZNL4zqNxKLunY37DbMfsijcrosruEz0jP5B8" alt="">
        <div class="case__badge badge-tr">+42% Retention</div>
      </div>
      <div class="case__body">
        <h3 class="zh">数字礼宾：高定精品馆</h3><h3 class="en" style="display:none">Aurelian Prime for Private Banking</h3>
        <p class="zh">为高净值客户打造超写实数字人，引领其在元宇宙私密展厅中探索收藏系列。</p>
        <p class="en" style="display:none">Reimagining wealth management through a hyper-realistic digital concierge.</p>
      </div>
    </div>
    <!-- 案例2: 小图 span 4 + 下移 -->
    <div class="case case-2">
      <div class="case__media">
        <img class="case__img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAbzyCKncNddZWI4AL9W1PzTrf87dAdc15bSa6Fd3YrSSGFEuBfJ3GhCFLTHnITaPoy4NCIEIDZOeUKsHJd6e2c7FDaew9HCkxeuTv03yNA4X7y-qTkiFY4MOvSk2zrCu5GQ_p65NrRMz_GNOBLtPNKFzrS-Ckc5gm5l8yNmgXbThtaUMNmdR8RX5fPFCm2HTkfsWAPWB_exmyy2S83jKAisRxFIzhTpkKULDmR2dsgcRNJKdBeEcyuvWMQJMYMmhDdxAF54pTW_lY" alt="">
        <div class="case__badge badge-bl">AI Art Integration</div>
      </div>
      <div class="case__body">
        <h3 class="zh">Lumina NFT 系列</h3><h3 class="en" style="display:none">Lumina NFT Series</h3>
        <p class="zh">独家 IP 合作，将生成算法与传统工艺融合。</p>
        <p class="en" style="display:none">Exclusive IP collaboration merging generative algorithms with heritage craft.</p>
      </div>
    </div>
    <!-- 案例3: 方形 span 6 -->
    <div class="case case-3">
      <div class="case__media">
        <img class="case__img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4H19rUAh5_bCz2v1ci1tseGa-Di5jpnBrAnHe-YgawYATxXGmS91wVn2gBXbFf_0wxBUsuUY2OiauRcb-ihfSKdfpgDOKtdR6DPZPZ5hw5AN05sbGZDMOlJEQ7CyYm2vkYhqAeH64sLBuIhllT8g3Xj4Qtxu37Ey2Ec9ghcAQALQT7gvWNeq6ZYILIiQ17Q68TUDhnEJH1gswkrK2eVMzgllPbeCSTXoDVClzV4puDtJEM4bQXxHHSDexA5tek-i9d0zjTNG9gCI" alt="">
        <div class="case__badge badge-tl">3.4x Conversion</div>
      </div>
      <div class="case__body">
        <h3 class="zh">电商进化论</h3><h3 class="en" style="display:none">E-commerce Evolution</h3>
        <p class="zh">将浏览转化为沉浸式策展体验。</p>
        <p class="en" style="display:none">Luxury retail performance scaling through personalized digital twin advisors.</p>
      </div>
    </div>
    <!-- 案例4: 大图 span 6 + 下移 -->
    <div class="case case-4">
      <div class="case__media">
        <img class="case__img" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA07OHePXio7nWOjPsAAu22p1aEQmProB1QS2hd7-Dql9qnXIqTKGADFpMMJtQ7qjpsHvNaVVo1dP2zxURta_vFC6fKely12_ZmG1HXrJqIAf2mbwUgFewx_rSk1qT5UVJHJxUcXL-PEUsEAOkAezFV3CHIZCY4WD19WyO0KAcbMDq6Xa5pLw1HP0yU4oC697R8GifXGbWNOELwTRpCdraofjCGcJNz0GeZHhTKRb7gscadQ6qmTOUHImlIz4vTnY55Hec6Z9fGPyQ" alt="">
        <div class="case__badge badge-br">IP Protection 100%</div>
      </div>
      <div class="case__body">
        <h3 class="zh">数字 IP 金库</h3><h3 class="en" style="display:none">The Digital IP Vault</h3>
        <p class="zh">AI 集成奢侈房产的全球 PR 审计与声誉管理。</p>
        <p class="en" style="display:none">Global PR audit and reputation management for AI-integrated luxury estates.</p>
      </div>
    </div>
  </div>
  <div class="pagi"><button class="pagi__dot on"></button><button class="pagi__dot"></button></div>
</section>

<section class="insights">
  <div class="insights-hdr">
    <h2><span class="zh">前沿洞察</span><span class="en" style="display:none">The Intelligence Journal</span></h2>
    <p><span class="zh">行业洞察与思想领导力</span><span class="en" style="display:none">INDUSTRY INSIGHTS & THOUGHT LEADERSHIP</span></p>
  </div>
  <div class="art-grid">
    <article class="art">
      <div class="art__iw"><div class="art__ph">✦</div></div>
      <span class="art__cat">Aesthetics</span>
      <h4 class="zh">机器中的幽灵：<em>定义</em> AI 之美</h4><h4 class="en" style="display:none">The Ghost in the Machine: <em>Defining</em> AI Beauty</h4>
      <p class="zh">为何传统品牌正走向超风格化的数字表达。</p><p class="en" style="display:none">Moving beyond uncanny valley into hyper-stylized digital.</p>
      <span class="art__rt">8 MIN READ</span>
    </article>
    <article class="art">
      <div class="art__iw"><div class="art__ph">◈</div></div>
      <span class="art__cat">Technology</span>
      <h4 class="zh">神经网络与丝绸：<em>未来</em>服务的织物</h4><h4 class="en" style="display:none">Neural Networks & Silk: Future Service</h4>
      <p class="zh">在不失去专属触感的前提下扩展个性化关怀。</p><p class="en" style="display:none">Scaling personalized attention without losing human touch.</p>
      <span class="art__rt">12 MIN READ</span>
    </article>
    <article class="art">
      <div class="art__iw"><div class="art__ph">❖</div></div>
      <span class="art__cat">Strategy</span>
      <h4 class="zh">新白手套：<em>AI</em> 作为终极礼宾</h4><h4 class="en" style="display:none">The New White Glove: AI as Ultimate Concierge</h4>
      <p class="zh">审视自动化高端体验时代中忠诚度的演变。</p><p class="en" style="display:none">Loyalty evolution in automated high-end experiences.</p>
      <span class="art__rt">6 MIN READ</span>
    </article>
  </div>
  <div class="pagi"><button class="pagi__dot on"></button></div>
</section>

<section class="consult">
  <div class="consult__glow"></div>
  <h2><span class="zh">准备好定义您的传承了吗？</span><span class="en" style="display:none">Ready to define your legacy?</span></h2>
  <p><span class="zh">加入全球领先的品牌 AI 数字员工计划。迈出第一步。</span><span class="en" style="display:none">Join the world's leading brands in the new era of digital human excellence.</span></p>
  <button class="consult__btn"><span class="zh">立即咨询</span><span class="en" style="display:none">Initiate Consultation</span></button>
</section>

<footer>
  <div class="links"><a href="#">Brand Story</a><a href="#">Sustainability</a><a href="#">Privacy</a><a href="#">Terms</a><a href="#">Contact</a></div>
  <p class="copy">© 2024 HIREAIPEOPLE. THE NEW ERA OF DIGITAL HUMAN EXCELLENCE.</p>
</footer>

<script>
function sw(l){document.querySelectorAll('.zh').forEach(e=>e.style.display=l==='zh'?'':'none');document.querySelectorAll('.en').forEach(e=>e.style.display=l==='en'?'':'none');document.querySelectorAll('.lang-btn').forEach(b=>b.classList.remove('on'));document.querySelector('.lang-btn[onclick*="'+l+'"]').classList.add('on')}
</script>


<?php get_footer(); ?>