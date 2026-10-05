<?php
/**
 * Reservation / Catering Request Form Component
 */
$csrfToken = generate_csrf_token();
?>
<section class="booking-section" id="reserveren">
    <div class="booking-header">
        <span class="section-badge" style="background:rgba(217,119,6,0.2); color:#fde68a;">Vrijblijvende Aanvraag</span>
        <h2>Reservering & Offerte Aanvragen</h2>
        <p>Vraag eenvoudig een offerte of reservering aan voor uw feest, evenement of buffet. Wij nemen zo snel mogelijk contact met u op!</p>
    </div>

    <form id="reservationForm" method="post" action="/api/reserve.php">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label" for="res_date">Gewenste datum *</label>
                <input type="date" class="form-control" id="res_date" name="date" required min="<?= date('Y-m-d') ?>">
            </div>

            <div class="form-group">
                <label class="form-label" for="res_time">Gewenste tijd *</label>
                <input type="text" class="form-control" id="res_time" name="time" placeholder="bijv. 18:00" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="res_name">Volledige naam *</label>
                <input type="text" class="form-control" id="res_name" name="name" placeholder="Uw voor- en achternaam" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="res_email">E-mailadres *</label>
                <input type="email" class="form-control" id="res_email" name="email" placeholder="naam@voorbeeld.nl" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="res_phone">Telefoonnummer *</label>
                <input type="tel" class="form-control" id="res_phone" name="phone" placeholder="06 12345678" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="res_guests">Aantal personen *</label>
                <input type="number" class="form-control" id="res_guests" name="guests" min="1" placeholder="bijv. 25" required>
            </div>

            <div class="form-group full-width">
                <label class="form-label" for="res_buffet">Gewenst buffet type</label>
                <select class="form-control" id="res_buffet" name="buffet_type">
                    <option value="Budget Buffet (€18,50 p.p.)">Budget Buffet (€18,50 p.p.)</option>
                    <option value="Chef Special Buffet (€22,50 p.p.)">Chef Special Buffet (€22,50 p.p.)</option>
                    <option value="Vegetarisch Buffet (€15,50 p.p.)">Vegetarisch Buffet (€15,50 p.p.)</option>
                    <option value="Foodtruck op locatie">Foodtruck op locatie</option>
                    <option value="Maatwerk / Weet ik nog niet">Maatwerk / Weet ik nog niet</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label class="form-label" for="res_notes">Vragen of speciale wensen</label>
                <textarea class="form-control" id="res_notes" name="notes" placeholder="Vertel ons over uw wensen, allergieën of specifieke locatie..."></textarea>
            </div>

            <div class="form-group full-width" style="text-align:center; margin-top:1rem;">
                <button type="submit" class="btn btn-primary btn-lg" style="min-width:260px;">
                    Verstuur Aanvraag
                </button>
                <p style="margin-top:0.75rem; font-size:0.85rem; color:#a8a29e;">
                    Direct telefonisch contact? Bel ons gerust via <a href="tel:<?= e(CONTACT_PHONE_RAW) ?>" style="color:#f59e0b; font-weight:700;"><?= e(CONTACT_PHONE) ?></a>
                </p>
            </div>
        </div>
    </form>
    <div id="formFeedback"></div>
</section>
