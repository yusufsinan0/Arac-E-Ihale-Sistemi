<section class="content">
    <form method="post" id="form" action="{"admin/settings/save"|base_url}">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Genel Ayarlar</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="inputCompanyName">Site Adı</label>
                            <input type="text" id="inputCompanyName" name="setting[company_name]" class="form-control"
                                   value="{$settings->company_name}">
                        </div>
                        <div class="form-group">
                            <label for="inputCompanyLogo">Site Logo URL</label>
                            <input type="text" id="inputCompanyLogo" name="setting[company_logo]" class="form-control"
                                   value="{$settings->company_logo}">
                        </div>
                        <div class="form-group">
                            <label for="inputCompanyLogo">ReCaptcha Site Key</label>
                            <input type="text" id="inputCompanyLogo" name="setting[recaptchaSiteKey]" class="form-control"
                                   value="{$settings->recaptchaSiteKey}">
                        </div>
                        <div class="form-group">
                            <label for="inputCompanyLogo">ReCaptcha Secret Key</label>
                            <input type="text" id="inputCompanyLogo" name="setting[recaptchaSecretKey]" class="form-control"
                                   value="{$settings->recaptchaSecretKey}">
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Mail SMTP Ayarları</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="smtpHost">SMTP Sunucusu</label>
                            <br>
                            <input type="text" id="smtpHost" name="setting[smtp_host]" class="form-control" value="{$settings->smtp_host}">
                        </div>
                        <div class="form-group">
                            <label for="smtpUsername">SMTP Username</label>
                            <br>
                            <input type="text" id="smtpUsername" name="setting[smtp_username]" class="form-control" value="{$settings->smtp_username}">
                        </div>
                        <div class="form-group">
                            <label for="smtpHost">SMTP Parola (Kriptolu şekilde saklanır)</label>
                            <br>
                            <input type="password" id="smtpPassword" name="setting[smtp_password]" class="form-control" value="{$settings->smtp_password}">
                        </div>
                        <div class="form-group">
                            <label for="smtpPort">SMTP Port</label>
                            <br>
                            <input type="number" id="smtpPort" name="setting[smtp_port]" class="form-control" value="{$settings->smtp_port}">
                        </div>
                        <div class="form-group">
                            <label for="smtpSecure" class="form-label">SMTP Güvenliği</label>
                            <br>
                            <select name="setting[smtp_secure]" class="form-control" id="smtpSecure">
                                <option value="none" {if $settings->smtp_secure == 'no'}selected{/if}>Yok</option>
                                <option value="ssl" {if $settings->smtp_secure == 'ssl'}selected{/if}>SSL</option>
                                <option value="tls" {if $settings->smtp_secure == 'tls'}selected{/if}>TLS</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-info mailTest">Mail Test Et</a>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            {foreach $apis as $key => $api}
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{$api["name"]} Bağlantı Ayarları</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            {foreach $api["fields"] as $field}
                                <div class="form-group">
                                    <label for="inputCompanyLogo">{$field["DisplayName"]}</label>
                                    <input type="text" id="inputCompanyLogo"
                                           name="setting[{$api["name"]}_{$field["Name"]}]" class="form-control"
                                           {if $settings->{$field["ValueKey"]}}value="{$settings->{$field["ValueKey"]}}"
                                           {else}value="{$field["Default"]}"{/if}
                                    >
                                </div>
                            {/foreach}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            {/foreach}

        </div>
        <div class="row">
            <div class="col-12">
                <a href="#" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success float-right">Kaydet</button>
            </div>
        </div>
        <br>
    </form>
</section>
