<h2><?php _e("Conditional Recipient"); ?></h2>

<div class="conditional-recipient-list">
 <!--   <?php /*foreach ($args['recipiants'] as $recipient_index => $recipient_data): */?>
        <div class="conditional-recipient">
            <div class="conditional-recipient-email-field">
                <label>Email: <input type="email" name="wpcf7cr_recipiants[<?/*= $recipient_index */?>][email]"
                                     value="<?php /*$recipient_data['email'] */?>"></label>
            </div>
            <div class="conditional-recipient-or-list">
                <?php /*foreach ($recipient_data['or_structures'] as $or_structure_index => $or_structure): */?>
                    <div class="conditional-recipient-or">
                        <?php /*if ($or_structure_index > 0): */?>
                            <p class="conditional-recipient-or-header"><?php /*_e('OR'); */?></p>
                        <?php /*endif; */?>
                        <?php /*foreach ($or_structure['and_structures'] as $and_structure_index => $and_structure): */?>
                            <div class="conditional-recipient-and">
                                <div class="conditional-recipient-and-field-form">
                                    <label><?php /*_e('Field: ') */?> <input
                                                name="wpcf7cr_recipiants[<?/*= $recipient_index */?>][or_structures][<?/*= $or_structure_index */?>][<?/*= $and_structure_index */?>][field]"
                                                type="text"
                                                value="<?/*= $and_structure['field'] */?>"></label>
                                </div>
                                <div class="conditional-recipient-and-operator-form">
                                    <?php /*wpcf7cr_select_form('wpcf7cr_recipiants[' . $recipient_index . '][or_structures][' . $or_structure_index . '][' . $and_structure_index . '][operator]', array('equal' => __('Equals'), 'notequal' => __('Not Equals'))); */?>
                                </div>
                                <div class="conditional-recipient-and-value-form">
                                    <label><?php /*_e('Value: ') */?> <input
                                                name="wpcf7cr_recipiants[<?/*= $recipient_index */?>][or_structures][<?/*= $or_structure_index */?>][<?/*= $and_structure_index */?>][value]"
                                                type="text"
                                                value="<?/*= $and_structure['value'] */?>"></label>
                                </div>
                                <a class="conditional-recipient-and-add-button button"><?php /*_e('AND') */?></a>
                                <?php /*if ($or_structure_index > 0 || $and_structure_index > 0): */?>
                                    <a class="conditional-recipient-and-del-button">-</a>
                                <?php /*endif; */?>
                            </div>
                        <?php /*endforeach; */?>

                    </div>
                <?php /*endforeach; */?>
                <a class="conditional-recipient-or-add-button button"><?php /*_e('Add new rules') */?></a>
            </div>
        </div>
        <a class="conditional-recipient-add-button button"><?php /*_e('Add Recipient') */?></a>
    --><?php /*endforeach; */?>
</div>
