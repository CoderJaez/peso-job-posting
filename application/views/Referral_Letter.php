<div class="modal fade" id="referral_letter">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Referral Letter
                </h4>
            </div>
            <div class="modal-body">
                <!-- legal size -->
                <div id="printArea">
                    <div class="rl-body">
                        <?php for ($x = 0; $x < 3; $x++) : ?>
                        <!-- 1 copy -->
                        <div class=" rl-1copy">
                            <!-- Header referral letter -->
                            <div class="rl-header">
                                <img src="[header_bg]" alt="">
                            </div>
                            <!-- Header content letter -->
                            <div class="rl-content">
                                <div class="rl-date">
                                    <span>[current_date]</span><br>
                                </div>

                                <center><strong><span>REFFERAL</span></strong></center>
                                <div class="rl-employerHeading">
                                    <span>[manager]</span><br>
                                    <span>[company]</span><br>
                                    <span>[address]</span><br>
                                    <br>
                                    <span>SIR:</span>
                                    <p class="rl-p"> This is to respectfully recommend for possible employment at your
                                        good
                                        office /
                                        establishment the herein applicant <strong>[applicant-name]</strong> of legal
                                        age
                                        and a resident
                                        of [applicant-address].
                                    </p>
                                    <p class="rl-p">This is to further recommend that the above-named person is an
                                        applicant
                                        as
                                        <strong>[position]</strong>
                                        bearing the Control No. [control-no] as per documents forwarded to the office
                                        undersigned.</p>
                                </div>
                                <!-- remarks -->
                                <div class="rl-remarks">
                                    <strong>
                                        <span>REMARKS:</span>
                                    </strong><br>
                                    <input type="checkbox" name="" id="">Hired ont the Spot(HOTS)<br>
                                    <input type="checkbox" name="" id="">Qualified(Q)<br>
                                    <input type="checkbox" name="" id="">Not Qualified(NQ)<br>
                                </div>
                                <!-- remarks -->
                                <br>

                                <!-- Signatories -->
                                <div class="rl-signatories">
                                    <p><span>ENGR. JOSEPH ISAIAS M. QUIPOT, VI, CE</span><br>
                                        City Administrator / PESO Manager &nbsp;&nbsp;&nbsp;&nbsp;</p>
                                </div>
                                <!-- Signatories -->

                            </div>
                            <!-- Header content letter -->
                        </div>
                        <?php endfor; ?>

                    </div>
                </div>


                <!-- legal size -->

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary pull-right" onclick="printDiv('printArea')"> print</button>

            </div>
        </div>
    </div>
</div>