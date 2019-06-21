<style>.card:last-of-type
    {
        margin-bottom: 0 !important;
    }</style>
<div class="card text_center">
    <div class="card text_center">
        <a href="/"><img class="text_center" src="/img/logo/circle_icon_sm.png"></a>
        <h1>Plans</h1>
        <h3>Choose an Account Plan</h3>
        <br>
        <p>We offer 6 Account Plans that allow you to choose between a wide range of resource limits / features.</p>
        <p>Our goal is to let you get exactly what you want out of your <?php echo SITE_NAME ?> account.</p>
        <br>
        <p>You can change your plan at any time.</p>
    </div>
    <br>
    <div class="width_100 text_center display_inline_block">
        <div class="card width_40 display_inline_block kek_flex margin_xsm_x padding_sm_y media_900_width_95">
            <h2>Basic Account</h2>
            <h4>0.025 BTC / mo</h4>
            <br>
            <p>API Tier 1</p>
            <p>Analytics Tier 1</p>
            <p>Simulation Tier 1</p>
            <p>Manual Trading Tier 1</p>
            <p>Automated Trading Tier 1</p>
            <p>Back Testing Tier 0</p>
            <p>Support Tier 0</p>
            <p>CryptoGuru Tier 0</p>
            <?php echo __html('button', [
                'class' => 'margin_xxsm_y',
                'color' => 'purple',
                'icon'  => 'subscriptions',
                'text'  => 'Select',
                'href'  => '/signup?plan=basic',
            ]) ?>
        </div>
        <div class="card width_40 display_inline_block kek_flex margin_xsm_x padding_sm_y media_900_width_95">
            <h2>Standard Account</h2>
            <h4>0.10 BTC / mo</h4>
            <br>
            <p>API Tier 2</p>
            <p>Analytics Tier 2</p>
            <p>Simulation Tier 2</p>
            <p>Manual Trading Tier 2</p>
            <p>Automated Trading Tier 2</p>
            <p>Back Testing Tier 1</p>
            <p>Support Tier 1</p>
            <p>CryptoGuru Tier 0</p>
            <?php echo __html('button', [
                'class' => 'margin_xxsm_y',
                'color' => 'purple',
                'icon'  => 'subscriptions',
                'text'  => 'Select',
                'href'  => '/signup?plan=standard',
            ]) ?>
        </div>
        <div class="card width_40 display_inline_block kek_flex margin_xsm_x padding_sm_y media_900_width_95">
            <h2>Premium Account</h2>
            <h4>0.15 BTC / mo</h4>
            <br>
            <p>API Tier 3</p>
            <p>Analytics Tier 3</p>
            <p>Simulation Tier 3</p>
            <p>Manual Trading Tier 3</p>
            <p>Automated Trading Tier 3</p>
            <p>Back Testing Tier 2</p>
            <p>Support Tier 2</p>
            <p>CryptoGuru Tier 1</p>
            <?php echo __html('button', [
                'class' => 'margin_xxsm_y',
                'color' => 'blue',
                'icon'  => 'subscriptions',
                'text'  => 'Select',
                'href'  => '/signup?plan=premium',
            ]) ?>
        </div>
        <div class="card width_40 display_inline_block kek_flex margin_xsm_x padding_sm_y media_900_width_95">
            <h2>Broker Account</h2>
            <h4>0.25 BTC / mo</h4>
            <br>
            <p>API Tier 4</p>
            <p>Analytics Tier 4</p>
            <p>Simulation Tier 4</p>
            <p>Manual Trading Tier 4</p>
            <p>Automated Trading Tier 4</p>
            <p>Back Testing Tier 3</p>
            <p>Support Tier 3</p>
            <p>CryptoGuru Tier 2</p>
            <?php echo __html('button', [
                'class' => 'margin_xxsm_y',
                'color' => 'blue',
                'icon'  => 'subscriptions',
                'text'  => 'Select',
                'href'  => '/signup?plan=broker',
            ]) ?>
        </div>
    </div>
    <div class="card width_90 display_inline_block kek_flex margin_xsm_x padding_sm_y media_900_width_95">
        <h2>Enterprise Account</h2>
        <p>Enterprise Accounts are allocated custom limits and/or features based on your request.</p>
        <br>
        <p>If you are interested in an Enterprise Account, we will need to get some more information about your business
            or project first. This is so that we can provide a custom experience based on your request. Click Request a
            Quote to begin the Approval Process.
        </p>
        <br>
        <p>API Tier X</p>
        <p>Analytics Tier X</p>
        <p>Simulation Tier X</p>
        <p>Manual Trading Tier X</p>
        <p>Automated Trading Tier X</p>
        <p>Back Testing Tier X</p>
        <p>Support Tier X</p>
        <p>CryptoGuru Tier X</p>
        <?php echo __html('button', [
            'class' => 'margin_xxsm_y',
            'color' => 'orange',
            'icon'  => 'subscriptions',
            'text'  => 'Request a Quote',
            'href'  => '/signup?plan=free',
        ]) ?>
    </div>
    <div class="card width_90 display_inline_block kek_flex margin_xsm_x padding_sm_y media_900_width_95">
        <h2>Free Account</h2>
        <h4>FREE</h4>
        <br>
        <p>API Tier 0</p>
        <p>Analytics Tier 1</p>
        <p>Simulation Tier 1</p>
        <p>Manual Trading Tier 0</p>
        <p>Automated Trading Tier 0</p>
        <p>Back Testing Tier 0</p>
        <p>Support Tier 0</p>
        <p>CryptoGuru Tier 0</p>
        <p>Advertisements Enabled</p>
        <?php echo __html('button', [
            'class' => 'margin_xxsm_y',
            'color' => 'green',
            'icon'  => 'subscriptions',
            'text'  => 'Select',
            'href'  => '/signup?plan=free',
        ]) ?>
    </div>
</div>