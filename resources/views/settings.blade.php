@extends('layouts.app')

@section('title', 'Settings')
@section('topbar-title', 'Settings')

@section('content')
<div class="page-heading">
    <div>
        <h1>Appearance</h1>
        <p>Customize the colors, background, and feel of your FinanceFlow workspace.</p>
    </div>
</div>

<style>
    .settings-page {
        color: var(--ff-text, #334155);
    }

    .settings-page *,
    .settings-page *::before,
    .settings-page *::after {
        box-sizing: border-box;
    }

    .theme-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, .85fr);
        gap: 22px;
        align-items: start;
    }

    .theme-panel {
        background: var(--ff-card-bg, #fff);
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        color: var(--ff-text, #334155);
    }

    .theme-panel h2 {
        margin: 0 0 7px;
        font-size: 16px;
        font-weight: 700;
        color: var(--ff-heading, #172b4d);
    }

    .theme-description {
        color: var(--ff-muted, #64748b);
        font-size: 13px;
        line-height: 1.65;
        margin: 0 0 22px;
    }

    .theme-modes {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .theme-mode {
        border: 1px solid var(--ff-border, #e2e8f0);
        background: var(--ff-input-bg, #fff);
        border-radius: 12px;
        padding: 12px;
        text-align: left;
        cursor: pointer;
        color: var(--ff-text, #334155);
        font: inherit;
    }

    .theme-mode.selected {
        border: 2px solid var(--ff-accent, #16845b);
        padding: 11px;
        background: var(--ff-row-hover, #f8fafc);
    }

    .theme-mode:focus-visible,
    .theme-secondary:focus-visible,
    .theme-primary:focus-visible {
        outline: 2px solid var(--ff-accent, #16845b);
        outline-offset: 3px;
    }

    .theme-mode strong {
        display: block;
        margin-top: 10px;
        font-size: 13px;
    }

    .theme-mode small {
        display: block;
        color: var(--ff-muted, #64748b);
        margin-top: 3px;
        font-size: 11px;
    }

    .mode-swatch {
        display: block;
        height: 65px;
        border-radius: 8px;
        border: 1px solid var(--ff-border, #e2e8f0);
    }

    .theme-color-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px 14px;
    }

    .theme-field {
        min-width: 0;
        margin-bottom: 18px;
    }

    .theme-color-grid .theme-field {
        margin-bottom: 0;
    }

    .theme-field label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--ff-text, #334155);
    }

    .theme-field input[type="color"] {
        display: block;
        width: 100%;
        height: 43px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 9px;
        padding: 4px;
        background: var(--ff-input-bg, #fff);
        cursor: pointer;
    }

    .theme-field input[type="range"] {
        width: 100%;
        accent-color: var(--ff-accent, #16845b);
    }

    .theme-color-value {
        display: block;
        margin-top: 6px;
        color: var(--ff-muted, #64748b);
        font-family: monospace;
        font-size: 11px;
    }

    .theme-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 22px;
    }

    .theme-primary,
    .theme-secondary {
        min-height: 42px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 9px;
        padding: 11px 16px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        transition: opacity .18s ease, transform .18s ease;
    }

    .theme-primary {
        border-color: var(--ff-accent, #16845b);
        background: var(--ff-accent, #16845b);
        color: #fff;
    }

    .theme-secondary {
        background: var(--ff-input-bg, #fff);
        color: var(--ff-text, #334155);
    }

    .theme-primary:hover,
    .theme-secondary:hover {
        opacity: .86;
    }

    .theme-primary:active,
    .theme-secondary:active {
        transform: translateY(1px);
    }

    .theme-preview {
        overflow: hidden;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 14px;
        background: var(--ff-page-background, #f6f8fb);
    }

    .preview-background {
        min-height: 210px;
        padding: 20px;
        background: var(--ff-page-background, #f6f8fb);
    }

    .preview-card {
        padding: 18px;
        background: var(--ff-card-bg, #fff);
        color: var(--ff-text, #334155);
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(23, 43, 77, .05);
    }

    .preview-label {
        font-size: 12px;
        color: var(--ff-muted, #64748b);
    }

    .preview-amount {
        font-size: 25px;
        font-weight: 750;
        color: var(--ff-heading, #172b4d);
        margin: 7px 0 12px;
        overflow-wrap: anywhere;
    }

    .preview-description {
        font-size: 12px;
        line-height: 1.6;
        color: var(--ff-muted, #64748b);
        margin-bottom: 14px;
    }

    .preview-button {
        display: inline-block;
        border-radius: 7px;
        padding: 9px 12px;
        color: #fff;
        background: var(--ff-accent, #16845b);
        font-size: 12px;
        font-weight: 650;
    }

    .preview-input {
        display: block;
        width: 100%;
        margin-top: 15px;
        padding: 10px;
        border: 1px solid var(--ff-border, #e2e8f0);
        border-radius: 7px;
        background: var(--ff-input-bg, #fff);
        color: var(--ff-text, #334155);
        font-size: 12px;
    }

    .preview-caption {
        padding: 14px 18px;
        color: var(--ff-muted, #64748b);
        font-size: 12px;
        line-height: 1.6;
        background: var(--ff-card-bg, #fff);
        border-top: 1px solid var(--ff-border, #e2e8f0);
    }

    .theme-status {
        min-height: 20px;
        color: var(--ff-accent, #16845b);
        font-size: 13px;
        margin-top: 12px;
    }

    .theme-note {
        font-size: 12px;
        color: var(--ff-muted, #64748b);
        line-height: 1.65;
        margin-top: 18px;
    }

    [hidden] {
        display: none !important;
    }

    @media (max-width: 900px) {
        .theme-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .theme-panel {
            padding: 18px;
        }

        .theme-color-grid {
            gap: 16px 10px;
        }

        .theme-modes {
            gap: 8px;
        }

        .theme-actions > button {
            flex: 1 1 auto;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .settings-page * {
            transition: none !important;
        }
    }
</style>

<div class="settings-page">
    <div class="theme-layout">
        <div>
            <section class="theme-panel">
                <h2>Background style</h2>
                <p class="theme-description">
                    Use a gradient or a single color for your workspace background.
                </p>

                <div class="theme-modes">
                    <button type="button" class="theme-mode selected"
                            data-mode="gradient" aria-pressed="true">
                        <span class="mode-swatch"
                              style="background:linear-gradient(135deg,#e8f6ef,#eef2ff)"></span>
                        <strong>Gradient</strong>
                        <small>Blend two colors</small>
                    </button>

                    <button type="button" class="theme-mode"
                            data-mode="solid" aria-pressed="false">
                        <span class="mode-swatch" style="background:#ffffff"></span>
                        <strong>Solid color</strong>
                        <small>Use a single color</small>
                    </button>
                </div>

                <input type="hidden" id="themeMode" value="gradient">

                <div class="theme-field" style="margin-top:22px">
                    <label for="colorOne">Background color</label>
                    <input type="color" id="colorOne" value="#e8f6ef">
                    <span class="theme-color-value" id="colorOneValue">#E8F6EF</span>
                </div>

                <div class="theme-field" id="secondColorField">
                    <label for="colorTwo">Gradient ending color</label>
                    <input type="color" id="colorTwo" value="#eef2ff">
                    <span class="theme-color-value" id="colorTwoValue">#EEF2FF</span>
                </div>

                <div class="theme-field" id="angleField">
                    <label for="gradientAngle">
                        Gradient direction: <span id="angleValue">135</span>°
                    </label>
                    <input type="range" id="gradientAngle"
                           min="0" max="360" value="135">
                </div>
            </section>

            <section class="theme-panel">
                <h2>Surface colors</h2>
                <p class="theme-description">
                    Customize cards, form fields, table headers, and borders.
                </p>

                <div class="theme-color-grid">
                    <div class="theme-field">
                        <label for="surfaceColor">Cards &amp; sidebar</label>
                        <input type="color" id="surfaceColor" value="#ffffff">
                        <span class="theme-color-value" id="surfaceColorValue">#FFFFFF</span>
                    </div>

                    <div class="theme-field">
                        <label for="inputColor">Input backgrounds</label>
                        <input type="color" id="inputColor" value="#ffffff">
                        <span class="theme-color-value" id="inputColorValue">#FFFFFF</span>
                    </div>

                    <div class="theme-field">
                        <label for="tableColor">Table headers</label>
                        <input type="color" id="tableColor" value="#f7f9fc">
                        <span class="theme-color-value" id="tableColorValue">#F7F9FC</span>
                    </div>

                    <div class="theme-field">
                        <label for="borderColor">Borders</label>
                        <input type="color" id="borderColor" value="#e2e8f0">
                        <span class="theme-color-value" id="borderColorValue">#E2E8F0</span>
                    </div>
                </div>
            </section>

            <section class="theme-panel">
                <h2>Text and accent colors</h2>
                <p class="theme-description">
                    Fine-tune headings, regular text, secondary labels, and primary actions.
                </p>

                <div class="theme-color-grid">
                    <div class="theme-field">
                        <label for="headingColor">Headings</label>
                        <input type="color" id="headingColor" value="#172b4d">
                        <span class="theme-color-value" id="headingColorValue">#172B4D</span>
                    </div>

                    <div class="theme-field">
                        <label for="textColor">Body text</label>
                        <input type="color" id="textColor" value="#334155">
                        <span class="theme-color-value" id="textColorValue">#334155</span>
                    </div>

                    <div class="theme-field">
                        <label for="mutedColor">Secondary text</label>
                        <input type="color" id="mutedColor" value="#64748b">
                        <span class="theme-color-value" id="mutedColorValue">#64748B</span>
                    </div>

                    <div class="theme-field">
                        <label for="accentColor">Accent color</label>
                        <input type="color" id="accentColor" value="#16845b">
                        <span class="theme-color-value" id="accentColorValue">#16845B</span>
                    </div>
                </div>

                <p class="theme-note">
                    Accent colors are used for primary actions and highlights. Make sure
                    text remains readable against your chosen backgrounds.
                </p>

                <div class="theme-actions">
                    <button type="button" class="theme-primary" id="saveTheme">
                        Save theme
                    </button>

                    <button type="button" class="theme-secondary" id="resetTheme">
                        Reset defaults
                    </button>
                </div>

                <div class="theme-status" id="themeStatus"
                     role="status" aria-live="polite"></div>
            </section>
        </div>

        <div>
            <section class="theme-panel">
                <h2>Live preview</h2>
                <p class="theme-description">
                    Preview your colors before saving them.
                </p>

                <div class="theme-preview">
                    <div class="preview-background" id="previewBackground">
                        <div class="preview-card" id="previewCard">
                            <div class="preview-label" id="previewLabel">TOTAL BALANCE</div>
                            <div class="preview-amount" id="previewAmount">Rs. 85,420.00</div>

                            <div class="preview-description" id="previewDescription">
                                This is an example of secondary text in your dashboard.
                            </div>

                            <span class="preview-button" id="previewButton">
                                + Add transaction
                            </span>

                            <input class="preview-input" id="previewInput"
                                   value="Example input field" readonly
                                   aria-label="Example input preview">
                        </div>
                    </div>

                    <div class="preview-caption" id="previewCaption">
                        Your preview updates as you change colors.
                    </div>
                </div>

                <p class="theme-note">
                    Preview changes are temporary until you select <strong>Save theme</strong>.
                </p>
            </section>

            <section class="theme-panel">
                <h2>Quick white theme</h2>
                <p class="theme-description">
                    A clean, classic finance dashboard with white surfaces, dark text,
                    and green actions.
                </p>

                <button type="button" class="theme-secondary" id="whiteTheme">
                    Use clean white theme
                </button>
            </section>
        </div>
    </div>
</div>

<script>
(() => {
    const storageKey = 'financeflow-theme';

    const defaults = {
        mode: 'gradient',
        colorOne: '#e8f6ef',
        colorTwo: '#eef2ff',
        angle: 135,
        accent: '#16845b',
        surface: '#ffffff',
        input: '#ffffff',
        table: '#f7f9fc',
        border: '#e2e8f0',
        heading: '#172b4d',
        text: '#334155',
        muted: '#64748b'
    };

    const $ = id => document.getElementById(id);

    const fields = {
        mode: $('themeMode'),
        colorOne: $('colorOne'),
        colorTwo: $('colorTwo'),
        angle: $('gradientAngle'),
        accent: $('accentColor'),
        surface: $('surfaceColor'),
        input: $('inputColor'),
        table: $('tableColor'),
        border: $('borderColor'),
        heading: $('headingColor'),
        text: $('textColor'),
        muted: $('mutedColor')
    };

    function normalizeTheme(saved) {
        const theme = { ...defaults, ...(saved || {}) };

        Object.keys(defaults).forEach(key => {
            if (key === 'mode' || key === 'angle') return;

            if (typeof theme[key] !== 'string' ||
                !/^#[0-9a-f]{6}$/i.test(theme[key])) {
                theme[key] = defaults[key];
            }
        });

        theme.mode = theme.mode === 'solid' ? 'solid' : 'gradient';

        const angle = Number(theme.angle);
        theme.angle = Number.isFinite(angle)
            ? Math.min(360, Math.max(0, angle))
            : defaults.angle;

        return theme;
    }

    function readSavedTheme() {
        try {
            const saved = localStorage.getItem(storageKey);
            return normalizeTheme(saved ? JSON.parse(saved) : defaults);
        } catch (error) {
            return { ...defaults };
        }
    }

    function getTheme() {
        const theme = {
            mode: fields.mode.value,
            angle: Number(fields.angle.value)
        };

        Object.keys(fields).forEach(key => {
            if (key !== 'mode' && key !== 'angle') {
                theme[key] = fields[key].value;
            }
        });

        return normalizeTheme(theme);
    }

    function getBackground(theme) {
        return theme.mode === 'solid'
            ? theme.colorOne
            : `linear-gradient(${theme.angle}deg, ${theme.colorOne}, ${theme.colorTwo})`;
    }

    function applyTheme(theme) {
        const root = document.documentElement;
        const pageBackground = getBackground(theme);

        root.style.setProperty('--ff-page-background', pageBackground);
        root.style.setProperty('--ff-accent', theme.accent);
        root.style.setProperty('--ff-surface', theme.surface);
        root.style.setProperty('--ff-card-bg', theme.surface);
        root.style.setProperty('--ff-input-bg', theme.input);
        root.style.setProperty('--ff-table-head', theme.table);
        root.style.setProperty('--ff-border', theme.border);
        root.style.setProperty('--ff-heading', theme.heading);
        root.style.setProperty('--ff-text', theme.text);
        root.style.setProperty('--ff-muted', theme.muted);
        root.style.setProperty('--ff-row-hover', theme.table);

        /* Keep compatibility with the existing shared layout. */
        root.style.setProperty('--page', pageBackground);
        root.style.setProperty('--surface', theme.surface);
        root.style.setProperty('--text', theme.text);
        root.style.setProperty('--muted', theme.muted);
        root.style.setProperty('--border', theme.border);
        root.style.setProperty('--green', theme.accent);
        root.style.setProperty(
            '--green-light',
            `color-mix(in srgb, ${theme.accent} 12%, white)`
        );
    }

    function updateLabels(theme) {
        const values = {
            colorOneValue: theme.colorOne,
            colorTwoValue: theme.colorTwo,
            surfaceColorValue: theme.surface,
            inputColorValue: theme.input,
            tableColorValue: theme.table,
            borderColorValue: theme.border,
            headingColorValue: theme.heading,
            textColorValue: theme.text,
            mutedColorValue: theme.muted,
            accentColorValue: theme.accent
        };

        Object.entries(values).forEach(([id, value]) => {
            $(id).textContent = value.toUpperCase();
        });
    }

    function updatePreview() {
        const theme = getTheme();

        $('previewBackground').style.background = getBackground(theme);

        $('previewCard').style.backgroundColor = theme.surface;
        $('previewCard').style.color = theme.text;
        $('previewCard').style.borderColor = theme.border;

        $('previewLabel').style.color = theme.muted;
        $('previewAmount').style.color = theme.heading;
        $('previewDescription').style.color = theme.muted;

        $('previewButton').style.backgroundColor = theme.accent;

        $('previewInput').style.backgroundColor = theme.input;
        $('previewInput').style.color = theme.text;
        $('previewInput').style.borderColor = theme.border;

        $('previewCaption').style.backgroundColor = theme.surface;
        $('previewCaption').style.color = theme.muted;
        $('previewCaption').style.borderColor = theme.border;

        $('angleValue').textContent = theme.angle;
        $('secondColorField').hidden = theme.mode === 'solid';
        $('angleField').hidden = theme.mode === 'solid';

        document.querySelectorAll('[data-mode]').forEach(button => {
            const selected = button.dataset.mode === theme.mode;
            button.classList.toggle('selected', selected);
            button.setAttribute('aria-pressed', String(selected));
        });

        updateLabels(theme);
    }

    function loadFields(theme) {
        const normalized = normalizeTheme(theme);

        Object.keys(fields).forEach(key => {
            fields[key].value = normalized[key];
        });

        updatePreview();
    }

    function saveTheme(theme, message) {
        try {
            localStorage.setItem(storageKey, JSON.stringify(theme));
            applyTheme(theme);
            $('themeStatus').textContent = message;
            return true;
        } catch (error) {
            $('themeStatus').textContent =
                'Could not save the theme. Check your browser storage settings.';
            return false;
        }
    }

    document.querySelectorAll('[data-mode]').forEach(button => {
        button.addEventListener('click', () => {
            fields.mode.value = button.dataset.mode;
            updatePreview();
        });
    });

    Object.values(fields).forEach(field => {
        field.addEventListener('input', updatePreview);
        field.addEventListener('change', updatePreview);
    });

    $('saveTheme').addEventListener('click', () => {
        saveTheme(getTheme(), 'Your theme has been saved.');
    });

    $('resetTheme').addEventListener('click', () => {
        loadFields(defaults);
        saveTheme({ ...defaults }, 'Default colors restored.');
    });

    $('whiteTheme').addEventListener('click', () => {
        const whiteTheme = {
            ...defaults,
            mode: 'solid',
            colorOne: '#ffffff',
            colorTwo: '#ffffff',
            surface: '#ffffff',
            input: '#ffffff',
            table: '#f8fafc',
            border: '#e2e8f0',
            heading: '#172b4d',
            text: '#334155',
            muted: '#64748b',
            accent: '#16845b'
        };

        loadFields(whiteTheme);
        saveTheme(whiteTheme, 'Clean white theme applied and saved.');
    });

    const initialTheme = readSavedTheme();
    loadFields(initialTheme);
    applyTheme(initialTheme);
})();
</script>
@endsection
