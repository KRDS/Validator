# SELECT `id_label`, `fr_FR`, `en_US` FROM `i18n_locales_labels` WHERE `id_label` LIKE 'error_validation%' AND `fr_FR` IS NOT NULL

INSERT INTO `i18n_locales_labels` (`id_label`, `fr_FR`, `en_US`) VALUES
('error_validation_lower_than_date',	'Veuillez indiquer une date avant %s',	'Please indicate a date before %s'),
('error_validation_not_empty',	'Ce champ ne peut pas être vide',	'This field cannot be empty'),
('error_validation_date',	'Veuillez indiquer une date valide',	'Please indicate a valid date'),
('error_validation_greater_than_date',	'Veuillez indiquer une date après %s',	'Please indicate a date after %s'),
('error_validation_lower_than',	'Veuillez indiquer un nombre inférieur à %d',	'Please indicate a number lower than %d'),
('error_validation_greater_than',	'Veuillez indiquer un nombre supérieur à %d',	'Please indicate a number greater than %d'),
('error_validation_email',	'Veuillez saisir une adresse email valide',	'Please enter a valid email address'),
('error_validation_empty',	'Ce champ ne peut pas être renseigné',	'This field should be left empty'),
('error_validation_float',	'Veuillez saisir un nombre',	'Please enter a number'),
('error_validation_digits',	'Le champ ne peut contenir que des chiffres',	'The input must contain only digits'),
('error_validation_email_string',	'Type incorrect. Chaîne attendue',	'Invalid type given. String expected'),
('error_validation_depends_on_check',	'Veuillez vérifier le champ %s',	'Please check the field %s'),
('error_validation_depends_on_empty',	'Veuillez renseigner le champ %s',	'Please fill in the field %s'),
('error_validation_length_greater_than',	'Ce champ doit contenir plus de %d caractères',	'This field must contain more than %d chars'),
('error_validation_boolean',	'Ce champ ne peut contenir qu\'un booléen',	'This field can contain a boolean value only'),
('error_validation_in_array',	'Cette valeur n\'est pas acceptée',	'This value is not accepted'),
('error_validation_alphanumeric',	'Ce champ ne peut contenir que des lettres et des chiffres',	'This field can contain digits and letters only'),
('error_validation_required',	'Ce champ est obligatoire',	'This field is required'),
('error_validation_generic',	'Veuillez vérifier ce champ',	'Please check this field'),
('error_validation_length_lower_than',	'Ce champ doit contenir moins de %d caractères',	'This field must contain less than %d chars');