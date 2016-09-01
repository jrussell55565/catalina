SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `answer_text` longtext,
  `correct_answer` int(11) NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `correct_answer_text` varchar(800) DEFAULT NULL,
  `answer_pos` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  `control_type` int(11) DEFAULT NULL,
  `answer_desc` varchar(500) NOT NULL,
  `answer_parent_id` int(11) DEFAULT NULL,
  `answer_point` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `indx_answers_parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43608 ;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `group_id`, `answer_text`, `correct_answer`, `priority`, `correct_answer_text`, `answer_pos`, `parent_id`, `control_type`, `answer_desc`, `answer_parent_id`, `answer_point`) VALUES
(43562, 10686, '1', 1, 1, '', 1, 0, 1, '', 0, 0),
(43563, 10686, '2', 0, 2, '', 1, 0, 1, '', 0, 0),
(43564, 10686, '3', 0, 3, '', 1, 0, 1, '', 0, 0),
(43565, 10686, '4', 0, 4, '', 1, 0, 1, '', 0, 0),
(43566, 10687, '1', 1, 1, '', 1, 0, 1, '', 0, 0),
(43567, 10687, '2', 0, 2, '', 1, 0, 1, '', 0, 0),
(43568, 10687, '3', 0, 3, '', 1, 0, 1, '', 0, 0),
(43569, 10687, '4', 0, 4, '', 1, 0, 1, '', 0, 0),
(43570, 10688, '1', 1, 1, '', 1, 43562, 1, '', 0, 0),
(43571, 10688, '2', 0, 2, '', 1, 43563, 1, '', 0, 0),
(43572, 10688, '3', 0, 3, '', 1, 43564, 1, '', 0, 0),
(43573, 10688, '4', 0, 4, '', 1, 43565, 1, '', 0, 0),
(43574, 10689, '1', 1, 1, '', 1, 43566, 1, '', 0, 0),
(43575, 10689, '2', 0, 2, '', 1, 43567, 1, '', 0, 0),
(43576, 10689, '3', 0, 3, '', 1, 43568, 1, '', 0, 0),
(43577, 10689, '4', 0, 4, '', 1, 43569, 1, '', 0, 0),
(43578, 10690, 'Michael Schumacher', 1, 1, '', 1, 0, 1, '', 0, 0),
(43579, 10690, 'Bill Gates', 0, 2, '', 1, 0, 1, '', 0, 0),
(43580, 10690, 'Robert Miles', 0, 3, '', 1, 0, 1, '', 0, 0),
(43581, 10690, 'Bruce Lee', 0, 4, '', 1, 0, 1, '', 0, 0),
(43582, 10691, '2+2=4', 1, 1, '', 1, 0, 1, '', 0, 0),
(43583, 10691, '2*2=4', 1, 2, '', 1, 0, 1, '', 0, 0),
(43584, 10691, '2+2=3', 0, 3, '', 1, 0, 1, '', 0, 0),
(43585, 10691, '2*2=3', 0, 4, '', 1, 0, 1, '', 0, 0),
(43586, 10691, '2+2=7', 0, 5, '', 1, 0, 1, '', 0, 0),
(43587, 10691, '2/2=7', 0, 6, '', 1, 0, 1, '', 0, 0),
(43588, 10692, '1+2=?', 0, 1, '3', 1, 0, 1, '', 0, 0),
(43589, 10692, '1+3=?', 0, 2, '4', 1, 0, 1, '', 0, 0),
(43590, 10692, '1+4=?', 0, 3, '5', 1, 0, 1, '', 0, 0),
(43591, 10692, '1+5=?', 0, 4, '6', 1, 0, 1, '', 0, 0),
(43592, 10693, '', 0, 1, 'Microsoft', 1, 0, 1, '', 0, 0),
(43593, 10694, 'Michael Schumacher', 1, 1, '', 1, 43578, 1, '', 0, 0),
(43594, 10694, 'Bill Gates', 0, 2, '', 1, 43579, 1, '', 0, 0),
(43595, 10694, 'Robert Miles', 0, 3, '', 1, 43580, 1, '', 0, 0),
(43596, 10694, 'Bruce Lee', 0, 4, '', 1, 43581, 1, '', 0, 0),
(43597, 10695, '2+2=4', 1, 1, '', 1, 43582, 1, '', 0, 0),
(43598, 10695, '2*2=4', 1, 2, '', 1, 43583, 1, '', 0, 0),
(43599, 10695, '2+2=3', 0, 3, '', 1, 43584, 1, '', 0, 0),
(43600, 10695, '2*2=3', 0, 4, '', 1, 43585, 1, '', 0, 0),
(43601, 10695, '2+2=7', 0, 5, '', 1, 43586, 1, '', 0, 0),
(43602, 10695, '2/2=7', 0, 6, '', 1, 43587, 1, '', 0, 0),
(43603, 10696, '1+2=?', 0, 1, '3', 1, 43588, 1, '', 0, 0),
(43604, 10696, '1+3=?', 0, 2, '4', 1, 43589, 1, '', 0, 0),
(43605, 10696, '1+4=?', 0, 3, '5', 1, 43590, 1, '', 0, 0),
(43606, 10696, '1+5=?', 0, 4, '6', 1, 43591, 1, '', 0, 0),
(43607, 10697, '', 0, 1, 'Microsoft', 1, 43592, 1, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `answer_variants`
--

CREATE TABLE IF NOT EXISTS `answer_variants` (
  `id` int(11) NOT NULL,
  `variant_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `answer_variants`
--

INSERT INTO `answer_variants` (`id`, `variant_name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F'),
(7, 'G'),
(8, 'H');

-- --------------------------------------------------------

--
-- Table structure for table `app_users`
--

CREATE TABLE IF NOT EXISTS `app_users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `app_user_id` bigint(20) NOT NULL,
  `user_type` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Surname` varchar(255) DEFAULT NULL,
  `UserName` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `disabled` int(11) DEFAULT NULL,
  `app_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `comments` varchar(800) DEFAULT NULL,
  `inserted_by` int(11) DEFAULT NULL,
  `inserted_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `user_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asg_qbank_quizzes`
--

CREATE TABLE IF NOT EXISTS `asg_qbank_quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asg_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `view_priority` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asg_qbank_quizzes_fb1` (`asg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=221 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `asg_qbank_quizzes`
--

INSERT INTO `asg_qbank_quizzes` (`id`, `asg_id`, `quiz_id`, `view_priority`) VALUES
(15, 9, 152, 1),
(16, 9, 151, 2),
(17, 10, 154, 1);

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE IF NOT EXISTS `assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto increment',
  `quiz_id` int(11) NOT NULL DEFAULT '0' COMMENT 'generated quiz id',
  `org_quiz_id` int(11) NOT NULL DEFAULT '0' COMMENT 'selected original quiz_id',
  `results_mode` int(11) NOT NULL DEFAULT '0' COMMENT '1 = Point , 2 = Percent',
  `added_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Added date and time',
  `quiz_time` int(11) NOT NULL DEFAULT '0' COMMENT 'Test time (in minutes)',
  `show_results` int(11) NOT NULL DEFAULT '0' COMMENT 'Show results to user 1=yes, 2=no',
  `pass_score` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Success point/percent ',
  `quiz_type` int(11) NOT NULL DEFAULT '0' COMMENT '1 = Quiz , 2 = Survey',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0 = Added , 1 = Started , 2 = Stopped',
  `allow_review` int(11) NOT NULL DEFAULT '2' COMMENT 'Users can review correct answers . 1 = yes , 2 =no',
  `qst_order` int(11) NOT NULL DEFAULT '1' COMMENT 'Questions order  1 = By priority , 2 = Random',
  `answer_order` int(11) NOT NULL DEFAULT '1' COMMENT 'Answers order 1 = By priority , 2 = Random',
  `affect_changes` int(11) NOT NULL DEFAULT '2',
  `limited` int(11) NOT NULL COMMENT 'Number of attempts',
  `send_results` int(11) NOT NULL COMMENT 'Send quiz results by mail. 1 Auto , 2 manual',
  `accept_new_users` int(11) NOT NULL COMMENT 'Auto assign to newly registered users . 1 = yes , 2=no',
  `assignment_name` varchar(600) DEFAULT NULL COMMENT 'The name of assignment',
  `asg_quiz_type` int(11) NOT NULL DEFAULT '1' COMMENT '2 = if questions selected from Bank  1 = if selected quiz',
  `branch_id` int(11) DEFAULT NULL COMMENT 'id of the branch or school',
  `inserted_by` int(11) DEFAULT NULL COMMENT 'id of the user that created assignment',
  `inserted_date` datetime DEFAULT NULL COMMENT 'inserted date and time',
  `updated_by` int(11) DEFAULT NULL COMMENT 'id of user that updated assignment',
  `updated_date` datetime DEFAULT NULL COMMENT 'updated date and time',
  `allow_change_prev` int(11) NOT NULL DEFAULT '1' COMMENT 'Allow to change previous answers : 1 =yes, 0=no',
  `show_success_msg` int(11) NOT NULL DEFAULT '0' COMMENT 'Show success/unsuccess message after each question 1 =yes, 0=no',
  `is_random` int(11) NOT NULL DEFAULT '1' COMMENT '1 = All questions , 2 = Random questions',
  `random_qst_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Questions count if selected "Random questions"',
  `variants` int(11) NOT NULL DEFAULT '0' COMMENT 'count of variants',
  `show_intro` int(11) NOT NULL DEFAULT '1' COMMENT '1 = yes , 0 = no',
  `intro_text` varchar(3255) DEFAULT NULL COMMENT 'text of intro',
  `show_point_info` int(11) NOT NULL DEFAULT '0' COMMENT 'Show point information after each question 1 =yes, 0=no',
  `results_template_id` int(11) NOT NULL COMMENT 'id of the template from "result_templates" table',
  `cert_name` varchar(855) DEFAULT NULL COMMENT 'the name of the certificate folder from "certificates_folder" directory',
  `cert_enabled` int(11) DEFAULT '0' COMMENT '1 = certificate enabled , 2 = not enabled',
  `asg_cat_id` int(11) DEFAULT NULL COMMENT 'category id of the assignment',
  `mails_copy` varchar(255) DEFAULT NULL COMMENT 'Send copy of all mails to : ',
  `fb_users_list` int(11) DEFAULT '0',
  `fb_share` int(11) DEFAULT '0',
  `asg_image` varchar(255) DEFAULT NULL,
  `fb_allow_comments` varchar(255) DEFAULT '0',
  `short_desc` varchar(1255) DEFAULT NULL,
  `paused` int(4) DEFAULT '0',
  `v_start_time` datetime DEFAULT NULL,
  `v_end_time` datetime DEFAULT NULL,
  `calc_mode` int(11) DEFAULT '1',
  `ans_calc_mode` int(11) DEFAULT '1',
  `show_subject_name` int(11) DEFAULT '1',
  `fail_sbj_exam` int(11) DEFAULT '0',
  `random_type` int(11) DEFAULT '1',
  `ldap_users_list` int(11) DEFAULT '0',
  `asg_cost` decimal(18,4) DEFAULT '0.0000',
  `asg_rate_id` int(11) DEFAULT '-1',
  `qst_rate_id` int(11) DEFAULT '-1',
  `point_koe` decimal(18,2) DEFAULT NULL,
  `calc_pen` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`id`, `quiz_id`, `org_quiz_id`, `results_mode`, `added_date`, `quiz_time`, `show_results`, `pass_score`, `quiz_type`, `status`, `allow_review`, `qst_order`, `answer_order`, `affect_changes`, `limited`, `send_results`, `accept_new_users`, `assignment_name`, `asg_quiz_type`, `branch_id`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`, `allow_change_prev`, `show_success_msg`, `is_random`, `random_qst_count`, `variants`, `show_intro`, `intro_text`, `show_point_info`, `results_template_id`, `cert_name`, `cert_enabled`, `asg_cat_id`, `mails_copy`, `fb_users_list`, `fb_share`, `asg_image`, `fb_allow_comments`, `short_desc`, `paused`, `v_start_time`, `v_end_time`, `calc_mode`, `ans_calc_mode`, `show_subject_name`, `fail_sbj_exam`, `random_type`, `ldap_users_list`, `asg_cost`, `asg_rate_id`, `qst_rate_id`, `point_koe`, `calc_pen`) VALUES
(9, 153, -1, 1, '2015-05-20 00:47:28', 100, 1, '2.00', 1, 2, 2, 1, 1, 2, 1, 2, 2, 'Test Exam 1', 1, 1, 50006, '2015-05-20 00:47:28', NULL, NULL, 1, 0, 1, 0, 0, 0, '', 0, 1, '-1', 0, 7, '', 0, 0, '', '0', '', 0, NULL, NULL, 3, 0, 1, 0, 0, 0, '0.0000', -1, -1, '1.00', 0),
(10, 155, -1, 1, '2015-05-20 21:01:21', 95, 1, '2.00', 1, 1, 2, 1, 1, 2, 1, 2, 2, 'Test Exam 2', 1, 1, 50006, '2015-05-20 21:01:21', NULL, NULL, 1, 0, 1, 0, 0, 0, '', 0, 1, '-1', 0, 7, '', 0, 0, '', '0', '', 0, NULL, NULL, 3, 0, 1, 0, 0, 0, '0.0000', -1, -1, '1.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_diff_level_xreff`
--

CREATE TABLE IF NOT EXISTS `assignment_diff_level_xreff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asg_id` int(11) NOT NULL,
  `diff_id` int(11) NOT NULL,
  `qst_count` int(11) DEFAULT '0',
  `diff_point` decimal(10,2) DEFAULT '0.00',
  `quiz_id` int(11) DEFAULT NULL,
  `pen_point` decimal(18,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignment_diff_level_xreff_fb1` (`asg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=78 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `assignment_diff_level_xreff`
--

INSERT INTO `assignment_diff_level_xreff` (`id`, `asg_id`, `diff_id`, `qst_count`, `diff_point`, `quiz_id`, `pen_point`) VALUES
(43, 9, 1, 0, '2.00', 152, '0.00'),
(44, 9, 2, 0, '1.12', 152, '0.00'),
(45, 9, 3, 0, '2.22', 152, '0.00'),
(46, 9, 1, 0, '2.00', 151, '0.00'),
(47, 9, 2, 0, '1.12', 151, '0.00'),
(48, 9, 3, 0, '2.22', 151, '0.00'),
(49, 10, 1, 0, '1.00', 154, '0.00'),
(50, 10, 2, 0, '2.00', 154, '0.00'),
(51, 10, 3, 0, '3.00', 154, '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `assignment_pauses`
--

CREATE TABLE IF NOT EXISTS `assignment_pauses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `pause_type` int(11) NOT NULL,
  `pause_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_qst_views`
--

CREATE TABLE IF NOT EXISTS `assignment_qst_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_quiz_id` int(11) NOT NULL,
  `qst_id` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `subject_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assignment_qst_views_ibfk_1` (`user_quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `assignment_qst_views`
--

INSERT INTO `assignment_qst_views` (`id`, `user_quiz_id`, `qst_id`, `inserted_date`, `subject_id`) VALUES
(23, 14, 10462, '2015-05-20 00:48:01', 152),
(24, 14, 10461, '2015-05-20 00:52:57', 151),
(25, 15, 10462, '2015-05-20 01:19:57', 152),
(26, 15, 10461, '2015-05-20 01:19:59', 151),
(27, 16, 10462, '2015-05-20 01:20:08', 152),
(28, 16, 10461, '2015-05-20 01:20:10', 151),
(29, 17, 10462, '2015-05-20 01:20:19', 152),
(30, 17, 10461, '2015-05-20 01:20:20', 151),
(31, 18, 10462, '2015-05-20 01:20:29', 152),
(32, 18, 10461, '2015-05-20 01:20:31', 151),
(33, 19, 10467, '2015-05-20 21:01:38', 154);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_question_points`
--

CREATE TABLE IF NOT EXISTS `assignment_question_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_quiz_id` int(11) NOT NULL COMMENT 'id from user_quizzes table',
  `question_id` int(11) NOT NULL COMMENT 'id from questions table',
  `question_point` decimal(18,2) NOT NULL COMMENT 'maximum point for this question',
  `total_point` decimal(18,2) NOT NULL COMMENT 'point of user for this question',
  `question_percent` decimal(18,2) NOT NULL COMMENT 'maximum percent for this question',
  `total_percent` decimal(18,2) NOT NULL COMMENT 'percent of user for this question',
  `penalty_point` decimal(18,2) NOT NULL COMMENT 'calculated penalty point for this question',
  `question_apoint` decimal(18,2) NOT NULL,
  `is_true` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `assignment_question_points_ibfk_1` (`user_quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `assignment_question_points`
--

INSERT INTO `assignment_question_points` (`id`, `user_quiz_id`, `question_id`, `question_point`, `total_point`, `question_percent`, `total_percent`, `penalty_point`, `question_apoint`, `is_true`) VALUES
(39, 14, 10461, '2.00', '2.00', '50.00', '50.00', '0.00', '0.00', 0),
(40, 15, 10462, '2.00', '2.00', '50.00', '50.00', '0.00', '0.00', 0),
(41, 16, 10462, '2.00', '2.00', '50.00', '50.00', '0.00', '0.00', 0),
(42, 16, 10461, '2.00', '2.00', '50.00', '50.00', '0.00', '0.00', 0),
(43, 17, 10461, '2.00', '2.00', '50.00', '50.00', '0.00', '0.00', 0),
(44, 18, 10462, '2.00', '2.00', '50.00', '50.00', '0.00', '0.00', 0),
(45, 18, 10461, '2.00', '2.00', '50.00', '50.00', '0.00', '0.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_subjects`
--

CREATE TABLE IF NOT EXISTS `assignment_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `min_subject_point` decimal(10,2) NOT NULL,
  `pres_id` int(11) NOT NULL,
  `pres_duration` int(11) NOT NULL,
  `asg_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_subject_results`
--

CREATE TABLE IF NOT EXISTS `assignment_subject_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `subject_point` decimal(10,2) NOT NULL,
  `subject_success` int(11) NOT NULL,
  `user_quiz_id` int(11) NOT NULL,
  `subject_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subject_apoint` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quiz_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `assignment_subject_results_ibfk_1` (`user_quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Dumping data for table `assignment_subject_results`
--

INSERT INTO `assignment_subject_results` (`id`, `subject_id`, `subject_point`, `subject_success`, `user_quiz_id`, `subject_percent`, `subject_apoint`, `quiz_id`) VALUES
(29, 0, '0.00', 1, 14, '0.00', '0.00', 152),
(30, 0, '2.00', 1, 14, '50.00', '0.00', 151),
(32, 0, '2.00', 1, 15, '50.00', '0.00', 152),
(33, 0, '0.00', 1, 15, '0.00', '0.00', 151),
(35, 0, '2.00', 1, 16, '50.00', '0.00', 152),
(36, 0, '2.00', 1, 16, '50.00', '0.00', 151),
(38, 0, '0.00', 1, 17, '0.00', '0.00', 152),
(39, 0, '2.00', 1, 17, '50.00', '0.00', 151),
(41, 0, '2.00', 1, 18, '50.00', '0.00', 152),
(42, 0, '2.00', 1, 18, '50.00', '0.00', 151);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_themes_xreff`
--

CREATE TABLE IF NOT EXISTS `assignment_themes_xreff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asg_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `assignment_themes_xreff`
--

INSERT INTO `assignment_themes_xreff` (`id`, `asg_id`, `theme_id`, `quiz_id`) VALUES
(11, 7, 6, NULL),
(12, 7, 5, NULL),
(13, 7, 4, NULL),
(14, 8, 0, NULL),
(15, 8, 6, NULL),
(16, 8, 5, NULL),
(17, 8, 4, NULL),
(18, 9, 7, 1),
(19, 9, 5, 1),
(20, 9, 4, 1),
(21, 10, -1, 2),
(22, 10, 7, 1),
(23, 10, 5, 1),
(24, 10, -1, 1),
(25, 11, 0, 2),
(26, 11, 5, 1),
(27, 11, 4, 1),
(28, 12, 4, 1),
(29, 13, 7, 1),
(30, 13, 6, 1),
(31, 13, 5, 1),
(32, 13, 4, 1),
(33, 13, -1, 1),
(40, 24, -1, 2),
(41, 24, 7, 1),
(42, 24, 5, 1),
(43, 24, -1, 1),
(44, 7, 28, 135),
(45, 7, 27, 135),
(46, 7, 25, 134),
(47, 7, 24, 134),
(48, 8, 31, 138),
(49, 8, 30, 138);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_usergroup_xreff`
--

CREATE TABLE IF NOT EXISTS `assignment_usergroup_xreff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asg_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `assignment_usergroup_xreff_fb1` (`asg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=227 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `assignment_usergroup_xreff`
--

INSERT INTO `assignment_usergroup_xreff` (`id`, `asg_id`, `group_id`) VALUES
(11, 9, 1),
(12, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_users`
--

CREATE TABLE IF NOT EXISTS `assignment_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL DEFAULT '0',
  `user_type` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `variant_id` int(11) DEFAULT NULL,
  `already_checked` int(11) DEFAULT '0',
  `u_quiz_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assignment_users_uq_index` (`user_id`,`assignment_id`,`user_type`),
  KEY `assignment_id` (`assignment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

--
-- Dumping data for table `assignment_users`
--

INSERT INTO `assignment_users` (`id`, `assignment_id`, `user_type`, `user_id`, `variant_id`, `already_checked`, `u_quiz_id`) VALUES
(59, 9, 1, 50024, 0, 0, NULL),
(60, 9, 1, 50018, 0, 0, NULL),
(61, 9, 1, 50017, 0, 0, NULL),
(62, 9, 1, 50016, 0, 0, NULL),
(63, 9, 1, 50015, 0, 0, NULL),
(64, 9, 1, 50014, 0, 0, NULL),
(65, 9, 1, 50013, 0, 0, NULL),
(66, 9, 1, 50012, 0, 0, NULL),
(67, 9, 1, 50011, 0, 0, NULL),
(68, 9, 1, 50010, 0, 0, NULL),
(69, 9, 1, 50009, 0, 0, NULL),
(70, 10, 1, 50024, 0, 0, NULL),
(71, 10, 1, 50018, 0, 0, NULL),
(72, 10, 1, 50017, 0, 0, NULL),
(73, 10, 1, 50016, 0, 0, NULL),
(74, 10, 1, 50015, 0, 0, NULL),
(75, 10, 1, 50014, 0, 0, NULL),
(76, 10, 1, 50013, 0, 0, NULL),
(77, 10, 1, 50012, 0, 0, NULL),
(78, 10, 1, 50011, 0, 0, NULL),
(79, 10, 1, 50010, 0, 0, NULL),
(80, 10, 1, 50009, 0, 0, NULL);


-- --------------------------------------------------------

--
-- Table structure for table `assignment_user_variants`
--

CREATE TABLE IF NOT EXISTS `assignment_user_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(455) NOT NULL,
  `branch_desc` varchar(800) DEFAULT NULL,
  `system_row` int(11) NOT NULL DEFAULT '0',
  `self_reg` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_desc`, `system_row`, `self_reg`) VALUES
(1, 'Head Office', 'Description 1', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cats`
--

CREATE TABLE IF NOT EXISTS `cats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `inserted_by` int(11) DEFAULT NULL,
  `inserted_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cats`
--

INSERT INTO `cats` (`id`, `cat_name`, `branch_id`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`) VALUES
(7, 'Sample category', 1, 50006, '2015-05-20 00:08:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=252 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_name`) VALUES
(2, 'Afghanistan'),
(3, 'Egypt'),
(5, 'Albania'),
(6, 'Algeria'),
(7, 'American Samoa'),
(8, 'Virgin Islands, U.s.'),
(9, 'Andorra'),
(10, 'Angola'),
(11, 'Anguilla'),
(12, 'Antarctica'),
(13, 'Antigua And Barbuda'),
(14, 'Equatorial Guinea'),
(15, 'Argentina'),
(16, 'Armenia'),
(17, 'Aruba'),
(18, 'Ascension'),
(19, 'Azerbaijan'),
(20, 'Ethiopia'),
(21, 'Australia'),
(22, 'Bahamas'),
(23, 'Bahrain'),
(24, 'Bangladesh'),
(25, 'Barbados'),
(26, 'Belgium'),
(27, 'Belize'),
(28, 'Benin'),
(29, 'Bermuda'),
(30, 'Bhutan'),
(31, 'Bolivia'),
(32, 'Bosnia And Herzegovina'),
(33, 'Botswana'),
(34, 'Bouvet Island'),
(35, 'Brazil'),
(36, 'Virgin Islands, British'),
(37, 'British Indian Ocean Territory'),
(38, 'Brunei Darussalam'),
(39, 'Bulgaria'),
(40, 'Burkina Faso'),
(41, 'Burundi'),
(42, 'Chile'),
(43, 'China'),
(44, 'Cook Islands'),
(45, 'Costa Rica'),
(46, 'C?te D''ivoire'),
(47, 'Denmark'),
(48, 'Germany'),
(49, 'Saint Helena'),
(50, 'Diego Garcia'),
(51, 'Dominica'),
(52, 'Dominican Republic'),
(53, 'Djibouti'),
(54, 'Ecuador'),
(55, 'El Salvador'),
(56, 'Eritrea'),
(57, 'Estonia'),
(58, 'Europ?ische Union'),
(59, 'Falkland Islands (malvinas)'),
(60, 'Faroe Islands'),
(61, 'Fiji'),
(62, 'Finland'),
(63, 'France'),
(64, 'French Guiana'),
(65, 'French Polynesia'),
(66, 'French Southern Territories'),
(67, 'Gabon'),
(68, 'Gambia'),
(69, 'Georgia'),
(70, 'Ghana'),
(71, 'Gibraltar'),
(72, 'Grenada'),
(73, 'Greece'),
(74, 'Greenland'),
(75, 'European Union'),
(76, 'Guam'),
(77, 'Guatemala'),
(78, 'Guernsey'),
(79, 'Guinea'),
(80, 'Guinea-bissau'),
(81, 'Guyana'),
(82, 'Haiti'),
(83, 'Heard Island And Mcdonald Islands'),
(84, 'Honduras'),
(85, 'Hong Kong'),
(86, 'India'),
(87, 'Indonesia'),
(88, 'Isle Of Man'),
(89, 'Iraq'),
(90, 'Iran, Islamic Republic Of'),
(91, 'Ireland'),
(92, 'Iceland'),
(93, 'Israel'),
(94, 'Italy'),
(95, 'Jamaica'),
(96, 'Japan'),
(97, 'Yemen'),
(98, 'Jersey'),
(99, 'Jordan'),
(100, 'Cayman Islands'),
(101, 'Cambodia'),
(102, 'Cameroon'),
(103, 'Canada'),
(104, 'Kanarische Inseln'),
(105, 'Cape Verde'),
(106, 'Kazakhstan'),
(107, 'Qatar'),
(108, 'Kenya'),
(109, 'Kyrgyzstan'),
(110, 'Kiribati'),
(111, 'Cocos (keeling) Islands'),
(112, 'Colombia'),
(113, 'Comoros'),
(114, 'Congo, The Democratic Republic Of The'),
(115, 'Congo'),
(116, 'Korea, Democratic People''s Republic Of'),
(117, 'Korea, Republic Of'),
(118, 'Croatia'),
(119, 'Cuba'),
(120, 'Kuwait'),
(121, 'Lao People''s Democratic Republic'),
(122, 'Lesotho'),
(123, 'Latvia'),
(124, 'Lebanon'),
(125, 'Liberia'),
(126, 'Libyan Arab Jamahiriya'),
(127, 'Liechtenstein'),
(128, 'Lithuania'),
(129, 'Luxembourg'),
(130, 'Macao'),
(131, 'Madagascar'),
(132, 'Malawi'),
(133, 'Malaysia'),
(134, 'Maldives'),
(135, 'Mali'),
(136, 'Malta'),
(137, 'Morocco'),
(138, 'Marshall Islands'),
(139, 'Martinique'),
(140, 'Mauritania'),
(141, 'Mauritius'),
(142, 'Mayotte'),
(143, 'Macedonia, The Former Yugoslav Republic Of'),
(144, 'Mexico'),
(145, 'Micronesia, Federated States Of'),
(146, 'Moldova'),
(147, 'Monaco'),
(148, 'Mongolia'),
(149, 'Montserrat'),
(150, 'Mozambique'),
(151, 'Myanmar'),
(152, 'Namibia'),
(153, 'Nauru'),
(154, 'Nepal'),
(155, 'New Caledonia'),
(156, 'New Zealand'),
(157, 'Neutrale Zone'),
(158, 'Nicaragua'),
(159, 'Netherlands'),
(160, 'Netherlands Antilles'),
(161, 'Niger'),
(162, 'Nigeria'),
(163, 'Niue'),
(164, 'Northern Mariana Islands'),
(165, 'Norfolk Island'),
(166, 'Norway'),
(167, 'Oman'),
(168, 'Austria'),
(169, 'Pakistan'),
(170, 'Palestinian Territory, Occupied'),
(171, 'Palau'),
(172, 'Panama'),
(173, 'Papua New Guinea'),
(174, 'Paraguay'),
(175, 'Peru'),
(176, 'Philippines'),
(177, 'Pitcairn'),
(178, 'Poland'),
(179, 'Portugal'),
(180, 'Puerto Rico'),
(181, 'R?union'),
(182, 'Rwanda'),
(183, 'Romania'),
(184, 'Russian Federation'),
(185, 'Solomon Islands'),
(186, 'Zambia'),
(187, 'Samoa'),
(188, 'San Marino'),
(189, 'Sao Tome And Principe'),
(190, 'Saudi Arabia'),
(191, 'Sweden'),
(192, 'Switzerland'),
(193, 'Senegal'),
(194, 'Serbien und Montenegro'),
(195, 'Seychelles'),
(196, 'Sierra Leone'),
(197, 'Zimbabwe'),
(198, 'Singapore'),
(199, 'Slovakia'),
(200, 'Slovenia'),
(201, 'Somalia'),
(202, 'Spain'),
(203, 'Sri Lanka'),
(204, 'Saint Kitts And Nevis'),
(205, 'Saint Lucia'),
(206, 'Saint Pierre And Miquelon'),
(207, 'Saint Vincent And The Grenadines'),
(208, 'South Africa'),
(209, 'Sudan'),
(210, 'South Georgia And The South Sandwich Islands'),
(211, 'Suriname'),
(212, 'Svalbard And Jan Mayen'),
(213, 'Swaziland'),
(214, 'Syrian Arab Republic'),
(215, 'Tajikistan'),
(216, 'Taiwan'),
(217, 'Tanzania, United Republic Of'),
(218, 'Thailand'),
(219, 'Timor-leste'),
(220, 'Togo'),
(221, 'Tokelau'),
(222, 'Tonga'),
(223, 'Trinidad And Tobago'),
(224, 'Tristan da Cunha'),
(225, 'Chad'),
(226, 'Czech Republic'),
(227, 'Tunisia'),
(228, 'Turkey'),
(229, 'Turkmenistan'),
(230, 'Turks And Caicos Islands'),
(231, 'Tuvalu'),
(232, 'Uganda'),
(233, 'Ukraine'),
(234, 'Union der Sozialistischen Sowjetrepubliken'),
(235, 'Uruguay'),
(236, 'Uzbekistan'),
(237, 'Vanuatu'),
(238, 'Holy See (vatican City State)'),
(239, 'Venezuela'),
(240, 'United Arab Emirates'),
(241, 'United States'),
(242, 'United Kingdom'),
(243, 'Viet Nam'),
(244, 'Wallis And Futuna'),
(245, 'Christmas Island'),
(246, 'Belarus'),
(247, 'Western Sahara'),
(248, 'Central African Republic'),
(249, 'Cyprus'),
(250, 'Hungary'),
(251, 'Montenegro');

-- --------------------------------------------------------

--
-- Table structure for table `d_controls`
--

CREATE TABLE IF NOT EXISTS `d_controls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `c_key` varchar(255) DEFAULT NULL,
  `c_text` varchar(1255) DEFAULT NULL,
  `c_type` int(11) NOT NULL,
  `c_type_param1` varchar(455) DEFAULT NULL,
  `t_style_name` varchar(100) DEFAULT NULL,
  `check_empty` int(11) NOT NULL DEFAULT '0',
  `val_id` int(11) NOT NULL,
  `val_err_msg` varchar(1255) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `def_value_type` int(11) DEFAULT NULL,
  `def_value_text` varchar(2255) DEFAULT NULL,
  `display_fm` varchar(1255) DEFAULT NULL,
  `max_len` int(11) DEFAULT NULL,
  `c_style_name` varchar(100) DEFAULT NULL,
  `enabled` int(11) DEFAULT '1',
  `c_type_param2` varchar(455) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `d_controls`
--

INSERT INTO `d_controls` (`id`, `c_key`, `c_text`, `c_type`, `c_type_param1`, `t_style_name`, `check_empty`, `val_id`, `val_err_msg`, `position`, `def_value_type`, `def_value_text`, `display_fm`, `max_len`, `c_style_name`, `enabled`, `c_type_param2`) VALUES
(1, 't_subject', 'Subject', 2, NULL, 't_style1', 1, 0, NULL, 1, 0, '1+1;', NULL, NULL, 'c_style1', 1, NULL),
(2, 't_body', 'Body', 3, 'null', 't_style1', 1, 0, NULL, 2, 2, 'get_ticket_body();', NULL, NULL, 'c_style_textarea1', 1, NULL),
(3, 'cat_id', 'Category', 1, '3', 't_style1', 1, 0, NULL, 3, NULL, NULL, NULL, NULL, 'c_style1', 1, NULL),
(5, 'tech_user_id', 'Technican', 1, '2', 't_style1', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'c_style1', 0, NULL),
(6, 'status_id', 'Status', 1, '2', 't_style1', 0, 0, NULL, 6, NULL, NULL, 'return isset($_GET[''id'']) ? 0 : 2 ;', NULL, 'c_style1', 1, '1'),
(7, 't_priority', 'Priority', 1, '4', 't_style1', 1, 0, NULL, 5, NULL, NULL, NULL, NULL, 'c_style1', 1, NULL),
(8, 'file', 'Attachment', 6, NULL, 't_style1', 0, 0, NULL, 7, NULL, NULL, NULL, NULL, 'c_style1', 1, NULL),
(9, 'value_text', 'Name', 2, NULL, 't_style1', 1, 0, NULL, 1, NULL, NULL, NULL, NULL, 'c_style1', 1, NULL),
(10, 'dic_id', '', 2, NULL, 't_style1', 1, 0, NULL, 2, 2, 'util::GetKeyID("did","?module=dic_names");', 'return "2";', NULL, 'c_style1', 1, NULL),
(13, 'level_name', 'Name', 2, NULL, 't_style1', 1, 0, 'Adı daxil edin', 1, NULL, NULL, NULL, NULL, 'c_style1', 1, NULL),
(14, 'level_desc', 'Description', 2, NULL, 't_style1', 0, 0, NULL, 2, NULL, NULL, NULL, NULL, 'c_style1', 1, NULL),
(21, 'level_point', 'Point', 2, NULL, 't_style1', 0, 0, NULL, 3, NULL, NULL, NULL, NULL, NULL, 1, NULL),
(25, 'level_pen', 'Penalty point', 2, NULL, 't_style1', 0, 0, NULL, 4, NULL, NULL, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `d_dics`
--

CREATE TABLE IF NOT EXISTS `d_dics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value_text` varchar(1200) DEFAULT NULL,
  `key_text` varchar(255) DEFAULT NULL,
  `dic_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `system_code` int(11) DEFAULT NULL,
  `system_row` int(11) NOT NULL DEFAULT '0',
  `tx_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `d_dics`
--

INSERT INTO `d_dics` (`id`, `value_text`, `key_text`, `dic_id`, `parent_id`, `position`, `system_code`, `system_row`, `tx_id`) VALUES
(23, 'Open', NULL, 2, NULL, NULL, 50, 1, 203),
(24, 'Closed', NULL, 2, NULL, NULL, 100, 1, 204),
(25, 'On Hold', NULL, 2, NULL, NULL, NULL, 1, 205),
(26, 'Question', NULL, 3, NULL, NULL, NULL, 0, 206),
(27, 'Problem', NULL, 3, NULL, NULL, NULL, 0, 207),
(28, 'Low', NULL, 4, NULL, NULL, NULL, 1, 208),
(29, 'Medium', NULL, 4, NULL, NULL, NULL, 1, 209),
(30, 'High', NULL, 4, NULL, NULL, NULL, 1, 210);

-- --------------------------------------------------------

--
-- Table structure for table `d_dic_names`
--

CREATE TABLE IF NOT EXISTS `d_dic_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dic_name` varchar(1255) DEFAULT NULL,
  `dic_desc` varchar(1255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `system_row` int(11) DEFAULT '0',
  `translate_array` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `d_dic_names`
--

INSERT INTO `d_dic_names` (`id`, `dic_name`, `dic_desc`, `parent_id`, `system_row`, `translate_array`) VALUES
(1, 'Departments', NULL, NULL, 0, NULL),
(2, 'Statuses', NULL, NULL, 1, 'STATUSES'),
(3, 'Categories', NULL, NULL, 0, NULL),
(4, 'Priorities', NULL, NULL, 1, 'PRIORITIES');

-- --------------------------------------------------------

--
-- Table structure for table `d_files`
--

CREATE TABLE IF NOT EXISTS `d_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tx_id` int(11) NOT NULL,
  `file_name` varchar(355) DEFAULT NULL,
  `real_file_name` varchar(355) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `d_pages`
--

CREATE TABLE IF NOT EXISTS `d_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(1200) DEFAULT NULL,
  `page_type` int(11) NOT NULL,
  `page_type_value` varchar(655) DEFAULT NULL,
  `success_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `d_pages`
--

INSERT INTO `d_pages` (`id`, `page_name`, `page_type`, `page_type_value`, `success_url`) VALUES
(1, 'Users', 1, 'tickets', '?module=tickets'),
(2, 'Dictionaries', 1, 'd_dics', '?module=dics&id=[did]'),
(4, 'Diff Levels', 1, 'qst_diff_levels', '?module=qst_diff_levels');

-- --------------------------------------------------------

--
-- Table structure for table `d_page_control_xreff`
--

CREATE TABLE IF NOT EXISTS `d_page_control_xreff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `control_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `d_page_control_xreff`
--

INSERT INTO `d_page_control_xreff` (`id`, `page_id`, `control_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 2, 9),
(9, 1, 8),
(10, 2, 10),
(11, 4, 13),
(12, 4, 14),
(13, 4, 21),
(14, 4, 25);

-- --------------------------------------------------------

--
-- Table structure for table `d_txs`
--

CREATE TABLE IF NOT EXISTS `d_txs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `tbl_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `d_vals`
--

CREATE TABLE IF NOT EXISTS `d_vals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc` varchar(255) DEFAULT NULL,
  `regexp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `d_values`
--

CREATE TABLE IF NOT EXISTS `d_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tx_id` int(11) NOT NULL,
  `control_id` int(11) NOT NULL,
  `control_value` varchar(2255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `vars` varchar(300) DEFAULT NULL,
  `body` text,
  `subject` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `vars`, `body`, `subject`) VALUES
(1, 'register_user', '[UserName],[Name],[Surname],[email], [address], [phone], [url]', 'Dear [Name] [Surname],\n\nYou have registered in our system. Here are the details of your registration :\n\nName : [Name] \nSurname  : [Surname]\nLogin : [UserName]\nAddress : [address]\nPhone : [phone]\nEmail : [email]\n\nTo confirm your registration in our Quiz system, please open the link below \n\n[url]\n\nThanks', 'Registration in Quiz System'),
(2, 'forgot_password', '[UserName],[Name],[Surname],[email],[address],[phone],[url],[random_password]', 'Dear [Name] [Surname],\n\nYou password has been reset , please find your new password below.\n\nLogin : [UserName]\nPassword : [random_password]\nEmail : [email]\n\nThanks', 'Password reset'),
(3, 'quiz_start_message', '[UserName],[Name],[Surname],[email],[address], [phone],[url],[quiz_name]', 'Dear [Name] [Surname], \n\nThe following exam has commenced :\n\nQuiz name : [assignment_name]\nName : [Name] \nSurname  : [Surname]\nLogin : [UserName]\n\nTo join the exam, please use the link below :\n\n[url]\n\nThanks', 'Online exam started'),
(4, 'quiz_results_success', '[UserName],[Name],[Surname],[email],[url],[assignment_name],[quiz_name],[start_date],[finish_date],[pass_score],[user_score],[level_name]', 'Dear [Name] [Surname] ,\n\nYou passed exam successfully . \n\nQuiz name : [assignment_name]\nStart date : [start_date]\nFinish date : [finish_date]\nPass score : [pass_score]\nYour score : [user_score]\n\nThanks,\nAdministrator', 'Exam success'),
(5, 'quiz_results_not_success', '[UserName],[Name],[Surname],[email],[url],[assignment_name],[quiz_name],[start_date],[finish_date],[pass_score],[user_score],[level_name]', 'Dear [Name] [Surname],\n\nSorry , you did not pass the following exam successfully\n\nQuiz name : [assignment_name]\nStart date : [start_date]\nFinish date : [finish_date]\nPass score : [pass_score]\nYour score : [user_score]\n\nThanks,\nAdministrator', 'Exam failure'),
(13, 'ticket_created', '[subject],[body],[category_name],[priority_name],[status_name],[creator_name],[url],[ticket_url]', 'Dear technicans,\n\nThe following ticket has been created :\n\nSubject : [subject]\nPriority : [priority_name]\nCategory : [category_name]\nCreated by : [creator_name]\n\nClick this link : [ticket_url]\n\nThanks', 'New ticket has been created - [subject]'),
(14, 'ticket_replied', '[subject],[body],[category_name],[priority_name],[status_name],[creator_name],[url],[ticket_url],[replier_name]', 'Dear employees,\n\nYou have reply to the following ticket : \n\nSubject : [subject]\nCreated by : [creator_name]\nReplied by : [replier_name]\n\nClick this link : [ticket_url]\n\nThanks', 'Reply - [subject]'),
(15, 'ticket_closed', '[subject],[body],[category_name],[priority_name],[status_name],[creator_name],[url],[ticket_url],[closer_name]', 'Dear [creator_name],\n\nYou ticket has been closed .\n\nSubject : [subject]\nCreated by : [creator_name]\nClosed by : [closer_name]\n\nClick this link : [ticket_url]\n\nThanks', 'Your ticket has been closed'),
(16, 'ticket_assigned', '[subject],[body],[category_name],[priority_name],[status_name],[creator_name],[url],[ticket_url],[assigner_name],[technican_name]', 'Dear [technican_name],\n\nA new ticket has been assigned to you\n\nSubject : [subject]\nCreated by : [creator_name]\nAssigned by : [assigner_name]\n\nClick this link : [ticket_url]\n\nThanks', 'A new ticket has been assigned to you');

-- --------------------------------------------------------

--
-- Table structure for table `imported_users`
--

CREATE TABLE IF NOT EXISTS `imported_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `surname` varchar(255) NOT NULL DEFAULT '',
  `user_name` varchar(150) NOT NULL DEFAULT '',
  `password` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(150) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `ip_banned_list`
--

CREATE TABLE IF NOT EXISTS `ip_banned_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(50) NOT NULL,
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `comments` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ip_res`
--

CREATE TABLE IF NOT EXISTS `ip_res` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `allow` int(11) NOT NULL DEFAULT '0',
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mailed_users`
--

CREATE TABLE IF NOT EXISTS `mailed_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_type` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `mail_type` int(11) NOT NULL,
  `user_quiz_id` int(11) NOT NULL,
  `arch` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mailed_users_ibfk_1` (`assignment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `mailed_users`
--

INSERT INTO `mailed_users` (`id`, `user_id`, `user_type`, `assignment_id`, `mail_type`, `user_quiz_id`, `arch`) VALUES
(14, 50017, 1, 36, 1, 0, '0'),
(15, 50014, 1, 36, 1, 0, '0'),
(16, 50014, 1, 36, 2, 27, '0'),
(17, 50015, 1, 36, 2, 28, '0');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id of module',
  `module_name` varchar(255) DEFAULT NULL COMMENT 'the name of menu item',
  `file_name` varchar(255) DEFAULT NULL COMMENT 'the file name from that should be in "modules" folder',
  `parent_id` int(11) DEFAULT '0' COMMENT 'parent id of menu . linked to id column of this table',
  `priority` int(11) DEFAULT '0' COMMENT 'order of menu item',
  `access_key` varchar(255) DEFAULT NULL COMMENT 'key to access this menu item from php code',
  `can_be_default` int(11) NOT NULL DEFAULT '0' COMMENT '1 =  can be used as default page for role , 0 cannot be',
  `module_icon` varchar(100) DEFAULT NULL,
  `enable_reports` int(11) DEFAULT '0',
  `is_visible` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `module_name`, `file_name`, `parent_id`, `priority`, `access_key`, `can_be_default`, `module_icon`, `enable_reports`, `is_visible`) VALUES
(1, 'Quizzes', NULL, 0, 1, NULL, 0, 'icon-folder', 0, 1),
(2, 'Categories', 'cats', 1, 1, 'cats', 1, NULL, 1, 1),
(3, 'Quizzes', 'quizzes', 1, 2, 'quizzes', 1, NULL, 1, 1),
(4, 'Local users', 'local_users', 13, 10, 'local_users', 1, NULL, 1, 1),
(5, 'Assignments', NULL, 0, 2, NULL, 0, 'icon-bell', 0, 1),
(6, 'Assignments', 'assignments', 5, 6, 'assignments', 1, NULL, 0, 1),
(7, 'New Assignment', 'add_assignment', 5, 7, 'add_assignment', 0, NULL, 0, 1),
(8, 'Assignments', NULL, 0, 3, 'user_assignments', 0, 'icon-bell', 0, 1),
(9, 'Active Assignments', 'active_assignments', 8, 1, 'active_assignments', 1, NULL, 0, 1),
(10, 'My old assigments', 'old_assignments', 8, 2, 'old_assignments', 1, NULL, 0, 1),
(11, 'New User', 'add_edit_user', 13, 70, 'add_edit_user', 0, NULL, 0, 1),
(12, 'New Quiz', 'add_edit_quiz', 1, 3, 'add_edit_quiz', 0, NULL, 0, 1),
(13, 'Users', '', 0, 4, '', 0, 'icon-user', 0, 1),
(17, 'Ratings', '', 0, 5, '', 0, 'icon-target', 0, 1),
(18, 'Ratings', 'ratings', 17, 1, 'ratings', 0, NULL, 0, 1),
(19, 'Add rating', 'add_edit_rating', 17, 2, 'add_edit_rating', 0, NULL, 0, 1),
(20, 'Change password', 'change_password', 13, 80, 'change_password', 0, NULL, 0, 1),
(21, 'Settings', NULL, 0, 6, NULL, 0, 'icon- icon-settings', 0, 1),
(22, 'Email templates', 'email_templates', 21, 1, 'email_templates', 0, NULL, 0, 1),
(23, 'Content management', 'cms&id=0', 21, 2, 'cms', 0, NULL, 0, 1),
(24, 'SQL Queries', 'sql_queries', 21, 9, 'sql_queries', 0, NULL, 0, 1),
(25, 'Test mail', 'test_mail', 21, 8, 'test_mail', 0, NULL, 0, 1),
(26, 'Imported users', 'imported_users', 13, 20, 'imported_users', 1, NULL, 0, 1),
(27, 'Questions bank', 'questions_bank', 1, 4, 'questions_bank', 1, NULL, 1, 1),
(28, 'Add question', 'add_question&quiz_id=-1&qstbank=1', 1, 5, 'add_question', 0, NULL, 0, 1),
(29, 'Roles', 'roles', 13, 50, 'roles', 0, NULL, 1, 1),
(30, 'Dashboard', 'default_page1', 0, -1, 'dashboard', 1, 'icon-home', 0, 1),
(31, 'Branches', 'branches', 21, 0, 'branches', 0, NULL, 0, 1),
(32, 'User groups', 'user_groups', 13, 60, 'user_groups', 0, NULL, 1, 1),
(33, 'Quiz result templates', 'qresult_templates', 21, 3, 'qresult_templates', 0, NULL, 0, 1),
(34, 'Test certificates', 'test_certificates', 21, 10, 'test_cert', 0, NULL, 0, 1),
(35, 'Facebook users', 'fb_users', 13, 30, 'fb_users', 1, NULL, 0, 1),
(36, 'Result levels', 'qresult_levels', 21, 3, 'result_temps', 0, NULL, 0, 1),
(37, 'Logs', 'user_logs', 21, 6, 'logs', 0, NULL, 0, 1),
(38, 'IP Banned list', 'ip_ban_list', 21, 5, 'ip_ban_list', 0, NULL, 0, 1),
(39, 'Subjects', 'subject_list', 1, 10, 'subjects', 0, NULL, 1, 1),
(40, 'Presentations', 'pres_list', 1, 15, 'pres', 0, NULL, 0, 1),
(41, 'LDAP users', 'ldap_users', 13, 40, 'ldap_users', 0, NULL, 0, 1),
(42, 'Test LDAP', 'test_ldap', 21, 7, 'test_ldap', 0, NULL, 0, 1),
(43, 'Helpdesk', NULL, 0, 5, 'helpdesk', 0, 'icon-lock', 0, 1),
(44, 'Helpdesk', 'tickets', 43, 5, 'tickets', 1, NULL, 1, 1),
(45, 'Ask a question', 'add_edit_ticket', 43, 10, 'create_ticket', 0, NULL, 0, 1),
(46, 'My Payments', NULL, 0, 7, NULL, 0, 'icon-diamond', 0, 1),
(47, 'My Balance', 'my_balance', 46, 1, 'my_balance', 1, NULL, 0, 1),
(48, 'Payment History', 'payment_history', 46, 5, 'payment_history', 1, NULL, 1, 1),
(49, 'View assignment', 'view_assignment', 5, 0, 'view_assignment', 0, '', 1, 0),
(50, 'All Payment History', 'all_payment_history', 46, 10, 'all_payment_history', 1, NULL, 1, 1),
(51, 'Question difficulty levels', 'qst_diff_levels', 21, 4, 'qst_diff_levels', 0, '', 0, 1),
(55, 'View details', 'view_details', 5, 0, 'view_details', 0, '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `modules_access`
--

CREATE TABLE IF NOT EXISTS `modules_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_name` varchar(355) NOT NULL COMMENT 'the name of access ',
  `parent_id` int(11) NOT NULL COMMENT 'id of module from "modules" table',
  `access_key` varchar(255) DEFAULT NULL COMMENT 'access key for accessing from php code',
  `priority` int(11) DEFAULT NULL COMMENT 'order ',
  PRIMARY KEY (`id`),
  KEY `FK_modules_access_modules_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=128 ;

--
-- Dumping data for table `modules_access`
--

INSERT INTO `modules_access` (`id`, `access_name`, `parent_id`, `access_key`, `priority`) VALUES
(1, 'View categories', 2, 'view_cats', NULL),
(2, 'Add category', 2, 'add_cat', NULL),
(3, 'Edit category', 2, 'edit_cat', NULL),
(4, 'Delete category', 2, 'delete_cat', NULL),
(5, 'View quizzes', 3, 'view_quizzes', NULL),
(6, 'Add quiz', 3, 'add_quiz', NULL),
(7, 'Edit quiz', 3, 'edit_quiz', NULL),
(8, 'Delete quiz', 3, 'delete_quiz', NULL),
(9, 'View questions', 27, 'view_qst', NULL),
(10, 'Add question', 27, 'add_qst', NULL),
(11, 'Edit question', 27, 'edit_qst', NULL),
(12, 'Delete question', 27, 'delete_qst', NULL),
(13, 'Move question', 27, 'move_qst', NULL),
(14, 'View assignments', 6, 'view_assignments', NULL),
(15, 'Add assignment', 6, 'add_assignment', NULL),
(16, 'Edit assignment', 6, 'edit_assignment', NULL),
(17, 'Delete assignment', 6, 'delete_assignment', NULL),
(18, 'Start assignment', 6, 'start_assignment', NULL),
(19, 'Stop assignment', 6, 'stop_assignment', NULL),
(20, 'Reports', 6, 'report_assignment', NULL),
(21, 'View notifications', 6, 'view_not', NULL),
(22, 'Add notification', 6, 'add_not', NULL),
(23, 'Delete notification', 6, 'delete_not', NULL),
(24, 'Send start mail', 6, 'send_start_mail_asg', NULL),
(25, 'Send results mail', 6, 'send_results_mail_asg', NULL),
(26, 'View quiz details', 6, 'view_quiz_details_asg', NULL),
(27, 'View information', 6, 'view_inf_asg', NULL),
(28, 'View roles', 29, 'view_roles', NULL),
(29, 'Add role', 29, 'add_role', NULL),
(30, 'Delete role', 29, 'delete_role', NULL),
(31, 'Edit role', 29, 'edit_role', NULL),
(32, 'View local users', 4, 'view_local_users', NULL),
(33, 'Add local user', 4, 'add_local_user', NULL),
(34, 'Edit local user', 4, 'edit_local_user', NULL),
(35, 'Delete local user', 4, 'delete_local_user', NULL),
(36, 'View user quizzes', 4, 'view_local_user_quizzes', NULL),
(37, 'View imported users', 26, 'view_imp_users', NULL),
(38, 'View user quizzes', 26, 'view_imp_user_quizzes', NULL),
(39, 'View mail templates', 22, 'view_mail_templates', NULL),
(40, 'Edit mail templates', 22, 'edit_mail_templates', NULL),
(41, 'View content management', 23, 'view_cms', NULL),
(42, 'Add page', 23, 'add_page', NULL),
(43, 'Edit page', 23, 'edit_page', NULL),
(44, 'Delete page', 23, 'delete_page', NULL),
(45, 'Access management', 29, 'acc_man', NULL),
(46, 'View branches', 31, 'view_branch', NULL),
(47, 'Add branch', 31, 'add_branch', NULL),
(48, 'Edit branch', 31, 'edit_branch', NULL),
(49, 'Delete branch', 31, 'delete_branch', NULL),
(50, 'Download certificate', 6, 'download_cert', NULL),
(51, 'Reset assignment', 6, 'reset_asg', NULL),
(52, 'Update user results', 6, 'update_user_results', NULL),
(53, 'View user groups', 32, 'view_groups', NULL),
(54, 'Delete user group', 32, 'delete_user_group', NULL),
(55, 'Add user group', 32, 'add_user_group', NULL),
(56, 'Edit user group', 32, 'edit_user_group', NULL),
(57, 'View result templates', 33, 'view_results_template', NULL),
(58, 'Delete result template', 33, 'delete_results_template', NULL),
(59, 'Add result template', 33, 'add_results_template', NULL),
(60, 'Edit result template', 33, 'edit_results_template', NULL),
(61, 'Add user to existing assignment', 6, 'add_user_asg', NULL),
(62, 'Remove user from existing assignment', 6, 'delete_user_asg', NULL),
(63, 'View facebook users', 35, 'view_fb_users', NULL),
(64, 'Add facebook users', 35, 'add_fb_user', NULL),
(65, 'Edit facebook users', 35, 'edit_fb_user', NULL),
(66, 'Delete facebook users', 35, 'delete_fb_user', NULL),
(67, 'View user quizzes', 35, 'view_fb_user_quizzes', NULL),
(68, 'Manage assignment questons', 6, 'man_asg_qsts', NULL),
(69, 'Print assignment questions', 6, 'print_asg_qsts', NULL),
(70, 'Manage levels', 33, 'manage_levels', NULL),
(71, 'Create a copy', 27, 'qst_copy', NULL),
(72, 'Change branch', 27, 'qst_change_brn', NULL),
(73, 'Change quiz', 27, 'qst_change_quiz', NULL),
(74, 'Create a copy', 3, 'quiz_copy', NULL),
(75, 'Change branch', 3, 'quiz_change_brn', NULL),
(76, 'Copy structure', 3, 'asg_copy_struct', NULL),
(77, 'Change branch', 3, 'asg_change_brn', NULL),
(78, 'Ip restrictions', 4, 'local_ip_res', 10),
(79, 'Ip restrictions', 26, 'imp_ip_res', 10),
(80, 'Ip restrictions', 35, 'fb_ip_res', 10),
(91, 'View LDAP users', 41, 'view_ldap_users', NULL),
(92, 'Add LDAP users', 41, 'add_ldap_user', NULL),
(93, 'Edit LDAP users', 41, 'edit_ldap_user', NULL),
(94, 'Delete LDAP users', 41, 'delete_ldap_user', NULL),
(95, 'View user quizzes', 41, 'view_ldap_user_quizzes', NULL),
(96, 'Ip restrictions', 41, 'app_ip_res', 10),
(100, 'View tickets', 44, 'view_tickets', NULL),
(101, 'Create ticket', 44, 'create_ticket', NULL),
(102, 'Edit ticket', 44, 'edit_ticket', NULL),
(103, 'Delete ticket', 44, 'delete_ticket', NULL),
(104, 'Assign ticket', 44, 'assign_ticket', NULL),
(105, 'Close ticket', 44, 'close_ticket', NULL),
(106, 'Mark as Unread', 44, 'unread_ticket', NULL),
(107, 'Read ticket', 44, 'read_ticket', NULL),
(108, 'Reply ticket', 44, 'reply_ticket', NULL),
(109, 'Delete reply', 44, 'delete_reply_ticket', NULL),
(110, 'Recalculate all', 6, 'recalc_all', NULL),
(111, 'Make corrections', 6, 'make_cor', NULL),
(112, 'Recalculate points', 6, 'recalc_points', NULL),
(113, 'Report issue', 6, 'rep_issue', NULL),
(114, 'Reset assignment', 6, 'reset_userquiz', NULL),
(115, 'Add minutes', 6, 'add_mins', NULL),
(116, 'User details', 4, 'local_usr_dtls', 15),
(117, 'User details', 26, 'imp_usr_dtls', 15),
(118, 'User details', 35, 'fb_usr_dtls', 15),
(119, 'User details', 41, 'app_usr_dtls', 15),
(120, 'View all payment history', 50, 'view_all_ph', 5),
(121, 'Delete all payment history', 50, 'delete_all_ph', 10),
(122, 'View exam logs', 6, 'view_exam_logs', NULL),
(123, 'Change exam status', 6, 'change_uq_status', NULL),
(124, 'Set as correct', 6, 'set_correct', NULL),
(125, 'Clear answers', 6, 'clear_answers', NULL),
(126, 'Archive questions', 27, 'arch_qst', NULL),
(127, 'Update child questions', 27, 'update_child_qst', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `module_reports`
--

CREATE TABLE IF NOT EXISTS `module_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `priority` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_reports_ibfk_1` (`report_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=564 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `module_reports`
--

INSERT INTO `module_reports` (`id`, `module_id`, `report_id`, `priority`) VALUES
(1, 27, 1, 1),
(2, 27, 2, 2),
(3, 27, 3, 3),
(4, 27, 4, 4),
(5, 2, 2, 5),
(6, 3, 4, 6),
(7, 39, 1, 7),
(8, 4, 5, 8),
(9, 4, 6, 9),
(10, 4, 7, 10),
(11, 29, 6, 11),
(12, 32, 7, 12),
(13, 49, 8, 13),
(14, 49, 9, 14),
(15, 49, 10, 15),
(16, 49, 11, 16),
(17, 49, 12, 17),
(18, 49, 13, 18),
(19, 44, 14, NULL),
(20, 44, 15, NULL),
(21, 44, 16, NULL),
(22, 44, 17, NULL),
(23, 44, 18, NULL),
(24, 44, 19, NULL),
(25, 48, 20, NULL),
(26, 50, 21, NULL),
(27, 50, 22, NULL),
(28, 50, 23, NULL),
(29, 50, 24, NULL),
(30, 27, 25, NULL),
(31, 55, 26, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nots`
--

CREATE TABLE IF NOT EXISTS `nots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asg_id` int(11) NOT NULL,
  `sent_by` int(11) NOT NULL,
  `body` varchar(255) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nots_ibfk_1` (`asg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(800) NOT NULL,
  `page_content` text NOT NULL,
  `priority` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `page_type` int(11) DEFAULT '1',
  `link_url` varchar(455) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_name`, `page_content`, `priority`, `parent_id`, `page_type`, `link_url`) VALUES
(1, 'Home', '', 0, 0, 1, NULL),
(2, 'About', '', 0, 0, 1, NULL),
(4, 'Links', '', 0, 0, 2, 'http://yandex.ru'),
(8, 'Products', '', 0, 0, 1, NULL),
(9, 'Documentation', '', 0, 0, 1, NULL),
(13, 'Information', '', 99, 0, 1, NULL),
(22, 'Page3', '', 3, 1, 1, NULL),
(21, 'Page2', '', 1, 1, 1, NULL),
(20, 'Page1', '', 0, 1, 1, NULL),
(23, 'Page4', '', 4, 1, 2, 'http://yandex.ru'),
(24, 'Page5', '<p>5</p>\r\n', 5, 1, 1, 'http://');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) DEFAULT NULL,
  `display_name` varchar(555) NOT NULL,
  `short_name` varchar(555) NOT NULL,
  `page_name` varchar(555) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `display_name`, `short_name`, `page_name`) VALUES
(1, 'Paypal', 'paypal', 'paypal');

-- --------------------------------------------------------

--
-- Table structure for table `payment_orders`
--

CREATE TABLE IF NOT EXISTS `payment_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(19) NOT NULL,
  `payer_email` varchar(75) NOT NULL,
  `mc_gross` decimal(18,4) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `dbt_crd` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `txn_id` (`txn_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=420 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pres`
--

CREATE TABLE IF NOT EXISTS `pres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pres_name` varchar(855) NOT NULL,
  `pres_desc` varchar(855) DEFAULT NULL,
  `pres_text` longtext,
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pres`
--

INSERT INTO `pres` (`id`, `pres_name`, `pres_desc`, `pres_text`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`, `branch_id`) VALUES
(1, 'my presentation 1', 'my presentation 1', '<p>my presentation 1</p>', 50024, '2015-05-16 02:05:15', NULL, NULL, 2),
(2, 'my presentation 2', 'my presentation 2', '<p>my presentation 2</p>', 50024, '2015-05-16 02:05:20', NULL, NULL, 2),
(3, 'my presentation 3', 'my presentation 3', '<p>my presentation 3</p>', 50024, '2015-05-16 02:05:26', NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `qst_diff_levels`
--

CREATE TABLE IF NOT EXISTS `qst_diff_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `level_desc` varchar(455) CHARACTER SET utf8 DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `level_point` decimal(18,2) DEFAULT '0.00',
  `level_pen` decimal(18,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AVG_ROW_LENGTH=4096 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `qst_diff_levels`
--

INSERT INTO `qst_diff_levels` (`id`, `level_name`, `level_desc`, `priority`, `level_point`, `level_pen`) VALUES
(1, 'Easy', 'Easy way', 1, '2.00', '0.00'),
(2, 'Medium', '', 2, '1.12', '0.00'),
(3, 'Hard', '', 3, '2.22', '0.00');

-- --------------------------------------------------------

--
-- Table structure for table `qst_diff_xreff`
--

CREATE TABLE IF NOT EXISTS `qst_diff_xreff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qst_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `diff_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_text` longtext,
  `question_type_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `point` decimal(18,0) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `header_text` varchar(1500) DEFAULT NULL,
  `footer_text` varchar(1500) DEFAULT NULL,
  `penalty_point` decimal(18,0) NOT NULL DEFAULT '0',
  `video_file` varchar(200) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `inserted_by` int(11) DEFAULT NULL,
  `inserted_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `success_msg` varchar(1200) DEFAULT NULL,
  `psuccess_msg` varchar(1200) DEFAULT NULL,
  `unsuccess_msg` varchar(1200) DEFAULT NULL,
  `subject_id` int(11) NOT NULL DEFAULT '0',
  `diff_level_id` int(11) DEFAULT '1',
  `qst_comments` varchar(1000) DEFAULT NULL,
  `qst_mode` int(11) DEFAULT '1',
  `is_arch` int(11) DEFAULT '0',
  `parent_quiz_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  KEY `indx_questions_parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10471 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_text`, `question_type_id`, `priority`, `quiz_id`, `point`, `added_date`, `parent_id`, `header_text`, `footer_text`, `penalty_point`, `video_file`, `branch_id`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`, `success_msg`, `psuccess_msg`, `unsuccess_msg`, `subject_id`, `diff_level_id`, `qst_comments`, `qst_mode`, `is_arch`, `parent_quiz_id`) VALUES
(10459, '<p>Math question 1</p>', 1, 1, 151, '0', '2015-05-19 22:21:46', 0, '', '', '0', NULL, 1, 50006, '2015-05-20 00:21:46', NULL, NULL, '', '', '', 32, 1, '', 1, 0, 151),
(10460, '<p>Eng question 1</p>', 1, 1, 152, '0', '2015-05-19 22:21:59', 0, '', '', '0', NULL, 1, 50006, '2015-05-20 00:21:59', NULL, NULL, '', '', '', 34, 1, '', 1, 0, 152),
(10461, '<p>Math question 1</p>', 1, 1, 153, '0', '2015-05-19 19:47:28', 10459, '', '', '0', '', 1, 50006, '2015-05-20 00:47:28', NULL, NULL, '', '', '', 32, 1, NULL, 1, 0, 151),
(10462, '<p>Eng question 1</p>', 1, 2, 153, '0', '2015-05-19 19:47:28', 10460, '', '', '0', '', 1, 50006, '2015-05-20 00:47:28', NULL, NULL, '', '', '', 34, 1, NULL, 1, 0, 152),
(10463, '<p>Who is in photo ?</p>\r\n\r\n<p><img alt="" src="/metro/ckeditor/kcfinder/upload_img/images/michael_schumacher1.jpg" style="width: 300px; height: 300px;" /></p>', 1, 1, 154, '0', '2015-05-20 18:56:47', 0, '', '', '0', NULL, 1, 50006, '2015-05-20 20:56:47', NULL, NULL, '', '', '', -1, 1, '', 1, 0, 154),
(10464, '<p><span style="line-height: 20.7999992370605px;">Which is correct</span></p>', 0, 2, 154, '0', '2015-05-20 18:58:43', 0, '', '', '0', NULL, 1, 50006, '2015-05-20 20:58:43', NULL, NULL, '', '', '', -1, 1, '', 1, 0, 154),
(10465, '<p><span style="line-height: 20.7999992370605px;">Please, answer below listed questions .</span></p>', 4, 3, 154, '0', '2015-05-20 18:59:42', 0, '', '', '0', NULL, 1, 50006, '2015-05-20 20:59:42', NULL, NULL, '', '', '', -1, 1, '', 1, 0, 154),
(10466, '<p><span style="line-height: 20.7999992370605px;">Enter the name of the biggest software company in the world</span></p>', 3, 4, 154, '0', '2015-05-20 19:00:27', 0, '', '', '0', NULL, 1, 50006, '2015-05-20 21:00:27', NULL, NULL, '', '', '', -1, 1, '', 1, 0, 154),
(10467, '<p>Who is in photo ?</p>\r\n\r\n<p><img alt="" src="/metro/ckeditor/kcfinder/upload_img/images/michael_schumacher1.jpg" style="width: 300px; height: 300px;" /></p>', 1, 1, 155, '0', '2015-05-20 16:01:21', 10463, '', '', '0', '', 1, 50006, '2015-05-20 21:01:21', NULL, NULL, '', '', '', -1, 1, NULL, 1, 0, 154),
(10468, '<p><span style="line-height: 20.7999992370605px;">Which is correct</span></p>', 0, 2, 155, '0', '2015-05-20 16:01:21', 10464, '', '', '0', '', 1, 50006, '2015-05-20 21:01:21', NULL, NULL, '', '', '', -1, 1, NULL, 1, 0, 154),
(10469, '<p><span style="line-height: 20.7999992370605px;">Please, answer below listed questions .</span></p>', 4, 3, 155, '0', '2015-05-20 16:01:21', 10465, '', '', '0', '', 1, 50006, '2015-05-20 21:01:21', NULL, NULL, '', '', '', -1, 1, NULL, 1, 0, 154),
(10470, '<p><span style="line-height: 20.7999992370605px;">Enter the name of the biggest software company in the world</span></p>', 3, 4, 155, '0', '2015-05-20 16:01:21', 10466, '', '', '0', '', 1, 50006, '2015-05-20 21:01:21', NULL, NULL, '', '', '', -1, 1, NULL, 1, 0, 154);

-- --------------------------------------------------------

--
-- Table structure for table `question_groups`
--

CREATE TABLE IF NOT EXISTS `question_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(450) NOT NULL,
  `show_header` int(11) NOT NULL,
  `group_total` decimal(18,0) NOT NULL,
  `show_footer` int(11) DEFAULT NULL,
  `check_total` decimal(18,0) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `group_name_eng` varchar(450) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `indx_question_grp_parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10698 ;

--
-- Dumping data for table `question_groups`
--

INSERT INTO `question_groups` (`id`, `group_name`, `show_header`, `group_total`, `show_footer`, `check_total`, `question_id`, `parent_id`, `group_name_eng`, `added_date`) VALUES
(10686, '', 0, '0', NULL, NULL, 10459, 0, NULL, '2015-05-19 22:21:46'),
(10687, '', 0, '0', NULL, NULL, 10460, 0, NULL, '2015-05-19 22:21:59'),
(10688, '', 0, '0', NULL, NULL, 10461, 10686, NULL, '2015-05-19 19:47:28'),
(10689, '', 0, '0', NULL, NULL, 10462, 10687, NULL, '2015-05-19 19:47:28'),
(10690, '', 0, '0', NULL, NULL, 10463, 0, NULL, '2015-05-20 18:56:47'),
(10691, '', 0, '0', NULL, NULL, 10464, 0, NULL, '2015-05-20 18:58:43'),
(10692, '', 0, '0', NULL, NULL, 10465, 0, NULL, '2015-05-20 18:59:42'),
(10693, 'Header text', 1, '0', NULL, NULL, 10466, 0, NULL, '2015-05-20 19:00:27'),
(10694, '', 0, '0', NULL, NULL, 10467, 10690, NULL, '2015-05-20 16:01:21'),
(10695, '', 0, '0', NULL, NULL, 10468, 10691, NULL, '2015-05-20 16:01:21'),
(10696, '', 0, '0', NULL, NULL, 10469, 10692, NULL, '2015-05-20 16:01:21'),
(10697, 'Header text', 1, '0', NULL, NULL, 10470, 10693, NULL, '2015-05-20 16:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE IF NOT EXISTS `question_types` (
  `id` int(11) NOT NULL,
  `question_type` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question_types`
--

INSERT INTO `question_types` (`id`, `question_type`) VALUES
(0, 'Multi answer (checkbox)'),
(1, 'One answer (radio button)'),
(3, 'Free text (textarea)'),
(4, 'Multi text (numbers only)');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `quiz_name` varchar(500) NOT NULL,
  `quiz_desc` varchar(500) NOT NULL,
  `added_date` datetime NOT NULL,
  `parent_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `inserted_by` int(11) DEFAULT NULL,
  `inserted_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=156 ;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `cat_id`, `quiz_name`, `quiz_desc`, `added_date`, `parent_id`, `branch_id`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`) VALUES
(151, 7, 'Mathematics', 'description', '2015-05-20 00:10:02', 0, 1, 50006, '2015-05-20 00:10:02', NULL, NULL),
(152, 7, 'English', 'description', '2015-05-20 00:10:16', 0, 1, 50006, '2015-05-20 00:10:16', NULL, NULL),
(153, 7, 'English', 'description', '2015-05-20 00:10:16', 152, 1, 50006, '2015-05-20 00:10:16', NULL, NULL),
(154, 7, 'General', 'General', '2015-05-20 20:51:01', 0, 1, 50006, '2015-05-20 20:51:01', NULL, NULL),
(155, 7, 'General', 'General', '2015-05-20 20:51:01', 154, 1, 50006, '2015-05-20 20:51:01', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(455) NOT NULL DEFAULT '',
  `temp_id` int(11) NOT NULL DEFAULT '0',
  `header_text` varchar(555) NOT NULL DEFAULT '',
  `footer_text` varchar(555) NOT NULL DEFAULT '',
  `img_count` int(11) NOT NULL DEFAULT '0',
  `show_results` int(11) NOT NULL DEFAULT '0',
  `restrict_user` int(11) NOT NULL DEFAULT '0',
  `bgcolor` varchar(255) NOT NULL DEFAULT '',
  `added_date` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `lang` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `description`, `temp_id`, `header_text`, `footer_text`, `img_count`, `show_results`, `restrict_user`, `bgcolor`, `added_date`, `status`, `lang`) VALUES
(26, 'rating 1', 6, '', '', 5, 1, 2, '-1', '2011-12-20 12:23:00', 1, 'English'),
(31, 'rating 2', 3, '', '', 5, 1, 2, '-1', '2011-12-20 12:23:19', 1, 'English'),
(32, 'rating 3', 1, '', '', 5, 1, 2, '-1', '2011-12-20 12:23:31', 1, 'English'),
(33, '123', 8, '', '', 15, 1, 2, '-1', '2015-02-28 01:32:31', 1, 'English');

-- --------------------------------------------------------

--
-- Table structure for table `rating_temps`
--

CREATE TABLE IF NOT EXISTS `rating_temps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `temp_name` varchar(255) NOT NULL DEFAULT '',
  `active_img` varchar(255) NOT NULL DEFAULT '',
  `inactive_img` varchar(255) NOT NULL DEFAULT '',
  `half_active_img` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `rating_temps`
--

INSERT INTO `rating_temps` (`id`, `temp_name`, `active_img`, `inactive_img`, `half_active_img`) VALUES
(1, 'Star1', '1.gif', '1_n.gif', '1_h.gif'),
(2, 'Star1', '7.gif', '7_n.gif', '7_h.gif'),
(3, 'Star2', '2.gif', '2_n.gif', '2_h.gif'),
(4, 'Star3', '3.gif', '3_n.gif', '3_h.gif'),
(5, 'Star4', '4.gif', '4_n.gif', '4_h.gif'),
(6, 'Star5', '5.gif', '5_n.gif', '5_h.gif'),
(7, 'Star6', '6.gif', '6_n.gif', '6_h.gif'),
(8, 'Star7', '8.gif', '8_n.gif', '8_h.gif');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_name` varchar(255) NOT NULL,
  `report_desc` varchar(555) DEFAULT NULL,
  `query` varchar(2255) NOT NULL,
  `shortcut` varchar(50) DEFAULT NULL,
  `report_method` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=744 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `report_name`, `report_desc`, `query`, `shortcut`, `report_method`) VALUES
(1, 'Questions count by theme', NULL, 'SELECT COUNT(*) as rcount, s.subject_name as rname FROM questions rep \r\nINNER JOIN subjects s ON s.id = rep.subject_id\r\nwhere rep.parent_id=0 [a:where]\r\nGROUP by s.subject_name', '1', NULL),
(2, 'Questions count by category', NULL, 'SELECT COUNT(*) as rcount, c.cat_name as rname FROM questions rep\r\nINNER JOIN quizzes qz ON qz.id = rep.quiz_id\r\nINNER JOIN cats c ON c.id = qz.cat_id\r\nwhere rep.parent_id=0 [a:where]\r\nGROUP by c.cat_name', '2', NULL),
(3, 'Questions count by question type', NULL, 'SELECT COUNT(*) as rcount, qt.question_type as rname FROM questions rep\r\nINNER JOIN question_types qt ON qt.id = rep.question_type_id\r\nwhere rep.parent_id=0 [a:where]\r\nGROUP by qt.question_type', '3', NULL),
(4, 'Questions count by subject', NULL, 'SELECT COUNT(*) as rcount, qz.quiz_name as rname FROM questions rep \r\nINNER JOIN quizzes qz ON qz.id = rep.quiz_id\r\nINNER JOIN cats c ON c.id = qz.cat_id\r\nwhere rep.parent_id=0 [a:where]\r\nGROUP by qz.quiz_name', '4', NULL),
(5, 'Local users count by country', NULL, 'SELECT COUNT(*) as rcount, c.country_name as rname FROM users rep \r\nINNER JOIN countries c ON c.id = rep.country_id\r\n[w:where]\r\nGROUP by c.country_name ', '5', NULL),
(6, 'Local users count by role', NULL, 'SELECT COUNT(*) as rcount,r.role_name as rname FROM users rep \r\nINNER JOIN roles r ON r.id=rep.user_type\r\n [w:where]  \r\nGROUP by r.role_name ', '6', NULL),
(7, 'Local users count by user groups', NULL, 'SELECT COUNT(*) as rcount,ug.group_name as rname FROM users rep\r\n INNER JOIN user_groups ug ON ug.id = rep.group_id\r\n [w:where] \r\n GROUP by ug.group_name ', '7', NULL),
(8, 'Top 10 Rankers for this exam', NULL, '   select * from (\r\n                  select  IF(rep.results_mode = 1 , pass_score_point , uq.pass_score_perc) rcount, u.FullName rname from assignments rep \r\n                  left join assignment_users asgu on rep.id=asgu.assignment_id \r\n                  left join v_all_users u on u.UserID=asgu.user_id\r\n                  left join user_quizzes uq on uq.assignment_id=rep.id and uq.user_id = u.UserID\r\n                  where pass_score_point<>0\r\n                  and uq.success is not null \r\n                  AND rep.id = [asg_id] [a:where]\r\n                  order by pass_score_point desc\r\n                ) usr LIMIT 0, 10', '8', NULL),
(9, 'Users count by finish status', NULL, 'SELECT IFNULL(uq.status,0) AS rname, COUNT(*) AS rcount \r\nFROM assignment_users au\r\nLEFT JOIN user_quizzes uq ON uq.assignment_id=au.assignment_id AND au.user_id = uq.user_id\r\nWHERE au.assignment_id = [asg_id]\r\nGROUP by uq.status;\r\n', '9', NULL),
(10, 'Users count by success status', NULL, 'SELECT uq.success as rname, COUNT(*) AS rcount \r\nFROM user_quizzes uq\r\nWHERE uq.assignment_id=[asg_id]\r\nGROUP by uq.success\r\n', '10', NULL),
(11, 'Users count by level', NULL, 'SELECT COUNT(*) AS rcount , rl.level_name as rname FROM user_quizzes uq \r\nINNER JOIN result_levels rl ON uq.level_id = rl.id\r\nWHERE uq.assignment_id=[asg_id]\r\nGROUP by rl.level_name', '11', NULL),
(12, 'Total success by subject name', NULL, 'SELECT COUNT(*) AS rcount ,s.subject_name as rname\r\nFROM assignment_subject_results asr\r\nINNER JOIN user_quizzes uq ON uq.id = asr.user_quiz_id\r\nINNER JOIN assignments a ON a.id =uq.assignment_id\r\nINNER JOIN subjects s ON s.id = asr.subject_id\r\nWHERE a.id = [asg_id] and asr.subject_success=1\r\nGROUP by asr.subject_success, s.subject_name', '12', NULL),
(13, 'Total fails by subject name', NULL, 'SELECT COUNT(*) AS rcount ,s.subject_name as rname\r\nFROM assignment_subject_results asr\r\nINNER JOIN user_quizzes uq ON uq.id = asr.user_quiz_id\r\nINNER JOIN assignments a ON a.id =uq.assignment_id\r\nINNER JOIN subjects s ON s.id = asr.subject_id\r\nWHERE a.id = [asg_id] and asr.subject_success=0\r\nGROUP by asr.subject_success, s.subject_name', '13', NULL),
(14, 'Total tickets by category created by me', NULL, 'SELECT dc.value_text AS rname, COUNT(*) as rcount\r\nFROM tickets t \r\nINNER JOIN d_txs tx on tx.id= t.tx_id\r\nLEFT JOIN d_dics dc ON dc.dic_id = 3 AND dc.id = cat_id\r\n[where]\r\nGROUP by dc.value_text', '14', 'add_access'),
(15, 'Total tickets by status created by me', NULL, 'SELECT ds.value_text AS rname, COUNT(*) AS rcount\r\nFROM tickets t \r\nINNER JOIN d_txs tx on tx.id= t.tx_id          \r\nLEFT JOIN d_dics ds ON ds.dic_id = 2 AND ds.id = status_id\r\n[where]\r\nGROUP by ds.value_text', '15', 'add_access'),
(16, 'Total tickets by priority created by me', NULL, 'SELECT dp.value_text AS rname, COUNT(*) AS rcount\r\nFROM tickets t \r\nINNER JOIN d_txs tx on tx.id= t.tx_id         \r\nLEFT JOIN d_dics dp ON dp.dic_id = 4 AND dp.id = t_priority \r\n[where]\r\nGROUP by dp.value_text\r\n', '16', 'add_access'),
(17, 'Total tickets count created by users', NULL, 'SELECT u.FullName as rname, COUNT(*) AS rcount\r\nFROM tickets t \r\nINNER JOIN d_txs rep on rep.id= t.tx_id\r\nLEFT JOIN v_all_users u ON rep.inserted_by = u.UserID      \r\n[w:where]\r\nGROUP by u.FullName', '17', NULL),
(18, 'Total tickets count assigned to technicancs', NULL, 'SELECT tu.FullName AS rname, COUNT(*) AS rcount\r\nFROM tickets t \r\nINNER JOIN d_txs rep on rep.id= t.tx_id\r\nLEFT JOIN v_all_users tu on tu.UserID = t.tech_user_id   WHERE t.tech_user_id is not NULL [a:where]   \r\nGROUP by tu.FullName', '18', NULL),
(19, 'Total ticket replies by users', NULL, 'SELECT  COUNT(*) AS rcount , val.FullName AS rname FROM ticket_replies rep \r\nINNER JOIN v_all_users val ON val.UserID = rep.inserted_by\r\n[w:where] ', '19', NULL),
(20, 'Total Payments for this user', NULL, 'SELECT SUM(po.mc_gross) rcount, po.dbt_crd AS rname  \r\nFROM payment_orders po\r\n[where]\r\nGROUP BY po.dbt_crd', '20', 'r20'),
(21, 'Total Payments', NULL, 'SELECT SUM(rep.mc_gross) rcount, rep.dbt_crd AS rname  \r\nFROM payment_orders rep\r\n[w:where]\r\nGROUP BY rep.dbt_crd', '21', 'add_currency'),
(22, 'Total debits by users', NULL, 'SELECT SUM(rep.mc_gross) rcount, val.FullName AS rname  \r\nFROM payment_orders rep\r\ninner join v_all_users val on val.UserID=rep.user_id\r\nwhere rep.dbt_crd=1  [a:where]\r\nGROUP BY val.FullName', '22', 'add_currency'),
(23, 'Total credits by users', NULL, 'SELECT SUM(rep.mc_gross) rcount, val.FullName AS rname  \r\nFROM payment_orders rep\r\ninner join v_all_users val on val.UserID=rep.user_id\r\nwhere rep.dbt_crd=2  [a:where]\r\nGROUP BY val.FullName', '23', 'add_currency'),
(24, 'Total debits by payment methods', NULL, 'SELECT pm.display_name as rname, SUM(rep.mc_gross) AS rcount\r\nFROM payment_orders rep \r\nINNER JOIN payment_methods pm ON pm.id=rep.payment_type_id  \r\nWHERE rep.payment_type=1 [a:where]\r\nGROUP BY pm.display_name', '24', 'add_currency'),
(25, 'Questions count by difficult level', NULL, 'SELECT COUNT(*) as rcount, rep.diff_level_id as rname FROM questions rep \r\nwhere rep.parent_id=0 [a:where]\r\nGROUP by rep.diff_level_id', '25', NULL),
(26, 'Correct and Wrong answers', NULL, 'SELECT (case aqp.is_true when 0 then IFNULL(SUM(IF(aqp.total_point<=0 , 0 ,1)),0) else 1 end) rcount,       \r\n        aqp.user_quiz_id,\r\n        ''Correct answers'' AS rname\r\nFROM assignment_question_points aqp\r\n  inner join questions q on q.id=aqp.question_id\r\nwhere aqp.user_quiz_id= [user_quiz_id]\r\nUNION \r\nSELECT (case aqp.is_true when 0 then IFNULL(SUM(IF(aqp.total_point<=0 , 1 ,0)),0) else 1 end) rcount,     \r\n        aqp.user_quiz_id,\r\n  ''Wrong answers'' AS rname\r\nFROM assignment_question_points aqp\r\n   inner join questions q on q.id=aqp.question_id\r\nwhere aqp.user_quiz_id= [user_quiz_id]', '26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `result_levels`
--

CREATE TABLE IF NOT EXISTS `result_levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `result_levels`
--

INSERT INTO `result_levels` (`id`, `level_name`) VALUES
(1, 'Poor'),
(2, 'Fair'),
(3, 'Avarage'),
(4, 'Very good'),
(5, 'Excellent');

-- --------------------------------------------------------

--
-- Table structure for table `result_templates`
--

CREATE TABLE IF NOT EXISTS `result_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(855) NOT NULL,
  `template_desc` varchar(855) DEFAULT NULL,
  `system_row` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `result_templates`
--

INSERT INTO `result_templates` (`id`, `template_name`, `template_desc`, `system_row`) VALUES
(1, 'Standard', 'T', 1);

-- --------------------------------------------------------

--
-- Table structure for table `result_template_contents`
--

CREATE TABLE IF NOT EXISTS `result_template_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) NOT NULL,
  `template_content` varchar(4000) DEFAULT NULL,
  `template_type` int(11) NOT NULL DEFAULT '0',
  `fb_message` varchar(800) DEFAULT NULL,
  `fb_name` varchar(800) DEFAULT NULL,
  `fb_link` varchar(800) DEFAULT NULL,
  `fb_description` varchar(800) DEFAULT NULL,
  `min_point` decimal(18,2) DEFAULT NULL,
  `max_point` decimal(18,2) DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  `c_temp_name` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_result_template_contents_result_templates_id` (`template_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `result_template_contents`
--

INSERT INTO `result_template_contents` (`id`, `template_id`, `template_content`, `template_type`, `fb_message`, `fb_name`, `fb_link`, `fb_description`, `min_point`, `max_point`, `level_id`, `c_temp_name`) VALUES
(1, 1, '<p align="left">\r\n	Dear [Name] [Surname] ,<br />\r\n	<br />\r\n	<span style="color:#ff0000;"><span style="font-size: 18px;"><span style="font-family: arial,helvetica,sans-serif;"><strong>Congratulations ! You have passed the following exam successfully :</strong></span></span></span><br />\r\n	<br />\r\n	Quiz name : [assignment_name]<br />\r\n	Start date : [start_date]<br />\r\n	Finish date : [finish_date]<br />\r\n	Pass score : [pass_score]<br />\r\n	Your score : [user_score]<br />\r\n	<br />\r\n	Thanks,<br />\r\n	Administrator</p>', 1, 'I have passed the following exam successfully. My point is [user_score]', 'Php Web Quiz - [assignment_name]', 'http://phpexamscript.net/?[start_date]', 'This is php web quiz . Exam starts soon . Try now.', NULL, NULL, 5, NULL),
(2, 1, '<p align="left">\r\n	Dear [Name] [Surname] ,</p>\r\n<div>\r\n	&nbsp;</div>\r\n<div>\r\n	<span style="font-size:18px;"><span style="font-family:arial,helvetica,sans-serif;"><strong><span style="color:#ff0000;">Sorry , you have not passed the following exam succesfully .&nbsp;</span></strong></span></span></div>\r\n<div>\r\n	&nbsp;</div>\r\n<div>\r\n	Quiz name : [assignment_name]</div>\r\n<div>\r\n	Start date : [start_date]</div>\r\n<div>\r\n	Finish date : [finish_date]</div>\r\n<div>\r\n	Pass score : [pass_score]</div>\r\n<div>\r\n	Your score : [user_score]</div>\r\n<div>\r\n	&nbsp;</div>\r\n<div>\r\n	Thanks,</div>\r\n<div>\r\n	Administrator</div>\r\n<p>\r\n	&nbsp;</p>\r\n<p>\r\n	&nbsp;</p>', 2, 'I have not passed the following exam successfully. My point is [user_score]', 'Php Web Quiz - [assignment_name]', 'http://phpexamscript.net/?[start_date]', 'This is php web quiz . Exam starts soon . Try now.', NULL, NULL, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(455) NOT NULL,
  `role_desc` varchar(800) DEFAULT NULL,
  `access_type` int(11) NOT NULL DEFAULT '1',
  `system_row` int(11) DEFAULT '0',
  `default_page` int(11) DEFAULT NULL,
  `allow_export` int(11) DEFAULT '1',
  `default_view` int(11) DEFAULT '1',
  `rec_mails` int(11) DEFAULT '0',
  `is_technican` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `role_desc`, `access_type`, `system_row`, `default_page`, `allow_export`, `default_view`, `rec_mails`, `is_technican`) VALUES
(1, 'System Administrators', 'system administrators', 1, 1, 30, 1, 1, 1, 1),
(2, 'Users', 'users', 3, 2, 9, 1, 1, 0, 0),
(9, 'Branch Administrator', 'Administrator of own branch', 2, 0, 30, 1, 1, 1, 1),
(10, 'Own Records Administrator', 'administrator of own records', 3, 0, 30, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles_access_rights`
--

CREATE TABLE IF NOT EXISTS `roles_access_rights` (
  `role_id` int(11) NOT NULL,
  `access_id` int(11) NOT NULL,
  KEY `roles_access_rights_ibfk_1` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles_access_rights`
--

INSERT INTO `roles_access_rights` (`role_id`, `access_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 50),
(1, 51),
(1, 52),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 45),
(1, 53),
(1, 54),
(1, 55),
(1, 56),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 46),
(1, 47),
(1, 48),
(1, 49),
(1, 39),
(1, 40),
(1, 57),
(1, 58),
(1, 59),
(1, 60),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(1, 61),
(1, 62),
(1, 63),
(1, 64),
(1, 65),
(1, 66),
(1, 67),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(10, 6),
(10, 7),
(10, 8),
(10, 9),
(10, 10),
(10, 11),
(10, 12),
(10, 13),
(10, 14),
(10, 15),
(10, 16),
(10, 17),
(10, 18),
(10, 19),
(10, 20),
(10, 21),
(10, 22),
(10, 23),
(10, 24),
(10, 25),
(10, 26),
(10, 27),
(10, 50),
(10, 51),
(10, 52),
(10, 61),
(10, 62),
(10, 28),
(10, 53),
(10, 32),
(10, 33),
(10, 34),
(10, 35),
(10, 36),
(10, 37),
(10, 38),
(10, 63),
(10, 64),
(10, 65),
(10, 66),
(10, 67),
(10, 46),
(10, 39),
(10, 57),
(10, 41),
(1, 68),
(10, 68),
(1, 69),
(1, 70),
(1, 71),
(1, 72),
(1, 73),
(1, 74),
(1, 75),
(1, 76),
(1, 77),
(1, 78),
(1, 79),
(1, 80),
(1, 91),
(1, 92),
(1, 93),
(1, 94),
(1, 95),
(1, 96),
(1, 100),
(1, 101),
(1, 102),
(1, 103),
(1, 104),
(1, 105),
(1, 106),
(1, 107),
(1, 108),
(1, 109),
(2, 100),
(2, 101),
(2, 105),
(2, 106),
(2, 107),
(2, 108),
(10, 100),
(10, 101),
(10, 105),
(10, 106),
(10, 107),
(10, 108),
(1, 110),
(1, 111),
(1, 112),
(1, 113),
(2, 113),
(1, 114),
(1, 115),
(1, 116),
(1, 117),
(1, 118),
(1, 119),
(10, 116),
(10, 117),
(10, 118),
(10, 119),
(1, 120),
(1, 121),
(1, 122),
(1, 123),
(1, 124),
(1, 125),
(1, 126),
(1, 127),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(9, 6),
(9, 7),
(9, 8),
(9, 9),
(9, 10),
(9, 11),
(9, 12),
(9, 13),
(9, 14),
(9, 15),
(9, 16),
(9, 17),
(9, 18),
(9, 19),
(9, 20),
(9, 21),
(9, 22),
(9, 23),
(9, 24),
(9, 25),
(9, 26),
(9, 27),
(9, 50),
(9, 51),
(9, 52),
(9, 61),
(9, 62),
(9, 68),
(9, 32),
(9, 33),
(9, 34),
(9, 35),
(9, 36),
(9, 116),
(9, 37),
(9, 38),
(9, 117),
(9, 63),
(9, 64),
(9, 65),
(9, 66),
(9, 67),
(9, 118),
(9, 119),
(9, 28),
(9, 53),
(9, 54),
(9, 55),
(9, 56),
(9, 100),
(9, 101),
(9, 105),
(9, 106),
(9, 107),
(9, 108),
(9, 46),
(9, 39),
(9, 41),
(9, 57);

-- --------------------------------------------------------

--
-- Table structure for table `roles_pages`
--

CREATE TABLE IF NOT EXISTS `roles_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `roles_pages_ibfk_1` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=154 ;

--
-- Dumping data for table `roles_pages`
--

INSERT INTO `roles_pages` (`id`, `role_id`, `page_id`) VALUES
(61, 2, 1),
(62, 2, 22),
(63, 2, 21),
(64, 2, 20),
(65, 2, 23),
(66, 2, 2),
(67, 2, 4),
(68, 2, 8),
(69, 2, 9),
(70, 2, 13),
(121, 10, 1),
(122, 10, 22),
(123, 10, 21),
(124, 10, 20),
(125, 10, 23),
(126, 10, 2),
(127, 10, 4),
(128, 10, 8),
(129, 10, 9),
(130, 10, 13),
(133, 1, 1),
(134, 1, 22),
(135, 1, 21),
(136, 1, 20),
(137, 1, 23),
(138, 1, 24),
(139, 1, 2),
(140, 1, 4),
(141, 1, 8),
(142, 1, 9),
(143, 1, 13),
(144, 9, 1),
(145, 9, 22),
(146, 9, 21),
(147, 9, 20),
(148, 9, 23),
(149, 9, 2),
(150, 9, 4),
(151, 9, 8),
(152, 9, 9),
(153, 9, 13);

-- --------------------------------------------------------

--
-- Table structure for table `roles_rights`
--

CREATE TABLE IF NOT EXISTS `roles_rights` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `roles_rights_ibfk_1` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=570 ;

--
-- Dumping data for table `roles_rights`
--

INSERT INTO `roles_rights` (`Id`, `role_id`, `module_id`) VALUES
(9, 2, 9),
(10, 2, 10),
(16, 2, 8),
(21, 2, 20),
(22, 2, 13),
(38, 4, 1),
(39, 4, 2),
(40, 4, 3),
(237, 1, 30),
(238, 1, 1),
(239, 1, 5),
(240, 1, 13),
(241, 1, 17),
(242, 1, 21),
(243, 1, 2),
(244, 1, 3),
(245, 1, 12),
(246, 1, 27),
(247, 1, 28),
(248, 1, 6),
(249, 1, 7),
(250, 1, 29),
(251, 1, 32),
(252, 1, 4),
(253, 1, 26),
(254, 1, 11),
(255, 1, 20),
(256, 1, 18),
(257, 1, 19),
(258, 1, 31),
(259, 1, 22),
(260, 1, 33),
(261, 1, 23),
(262, 1, 24),
(263, 1, 25),
(267, 5, 1),
(268, 5, 2),
(430, 1, 34),
(431, 1, 35),
(460, 10, 30),
(461, 10, 1),
(462, 10, 5),
(463, 10, 13),
(464, 10, 17),
(465, 10, 21),
(466, 10, 2),
(467, 10, 3),
(468, 10, 12),
(469, 10, 27),
(470, 10, 28),
(471, 10, 6),
(472, 10, 7),
(473, 10, 29),
(474, 10, 32),
(475, 10, 4),
(476, 10, 26),
(477, 10, 35),
(478, 10, 11),
(479, 10, 20),
(480, 10, 18),
(481, 10, 19),
(482, 10, 31),
(483, 10, 22),
(484, 10, 33),
(485, 10, 23),
(486, 10, 24),
(487, 10, 25),
(488, 1, 36),
(489, 1, 37),
(490, 1, 38),
(491, 1, 39),
(493, 10, 39),
(494, 1, 40),
(496, 10, 40),
(497, 1, 41),
(498, 1, 42),
(499, 1, 43),
(500, 1, 44),
(501, 1, 45),
(502, 1, 46),
(503, 1, 47),
(504, 1, 48),
(505, 2, 46),
(506, 2, 47),
(507, 2, 48),
(511, 10, 46),
(512, 10, 47),
(513, 10, 48),
(514, 2, 43),
(515, 2, 44),
(516, 2, 45),
(520, 10, 43),
(521, 10, 44),
(522, 10, 45),
(523, 1, 49),
(525, 10, 49),
(526, 1, 50),
(527, 11, 30),
(528, 11, 1),
(529, 11, 2),
(530, 1, 51),
(532, 1, 55),
(533, 9, 30),
(534, 9, 1),
(535, 9, 5),
(536, 9, 13),
(537, 9, 17),
(538, 9, 43),
(539, 9, 21),
(540, 9, 46),
(541, 9, 2),
(542, 9, 3),
(543, 9, 12),
(544, 9, 27),
(545, 9, 28),
(546, 9, 39),
(547, 9, 40),
(548, 9, 49),
(549, 9, 6),
(550, 9, 7),
(551, 9, 4),
(552, 9, 26),
(553, 9, 35),
(554, 9, 29),
(555, 9, 32),
(556, 9, 11),
(557, 9, 20),
(558, 9, 18),
(559, 9, 19),
(560, 9, 44),
(561, 9, 45),
(562, 9, 31),
(563, 9, 22),
(564, 9, 23),
(565, 9, 33),
(566, 9, 25),
(567, 9, 24),
(568, 9, 47),
(569, 9, 48);

-- --------------------------------------------------------

--
-- Table structure for table `role_reports`
--

CREATE TABLE IF NOT EXISTS `role_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_reports_ibfk_1` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=564 AUTO_INCREMENT=271 ;

--
-- Dumping data for table `role_reports`
--

INSERT INTO `role_reports` (`id`, `role_id`, `report_id`) VALUES
(21, 2, 20),
(22, 2, 14),
(23, 2, 15),
(24, 2, 16),
(194, 10, 1),
(195, 10, 2),
(196, 10, 3),
(197, 10, 4),
(198, 10, 5),
(199, 10, 6),
(200, 10, 7),
(201, 10, 8),
(202, 10, 9),
(203, 10, 10),
(204, 10, 11),
(205, 10, 12),
(206, 10, 13),
(207, 10, 14),
(208, 10, 15),
(209, 10, 16),
(210, 10, 17),
(211, 10, 18),
(212, 10, 19),
(213, 10, 20),
(214, 10, 21),
(215, 10, 22),
(216, 10, 23),
(217, 10, 24),
(218, 10, 25),
(220, 1, 1),
(221, 1, 2),
(222, 1, 3),
(223, 1, 4),
(224, 1, 5),
(225, 1, 6),
(226, 1, 7),
(227, 1, 8),
(228, 1, 9),
(229, 1, 10),
(230, 1, 11),
(231, 1, 12),
(232, 1, 13),
(233, 1, 14),
(234, 1, 15),
(235, 1, 16),
(236, 1, 17),
(237, 1, 18),
(238, 1, 19),
(239, 1, 20),
(240, 1, 21),
(241, 1, 22),
(242, 1, 23),
(243, 1, 24),
(244, 1, 25),
(245, 1, 26),
(246, 9, 1),
(247, 9, 2),
(248, 9, 3),
(249, 9, 4),
(250, 9, 5),
(251, 9, 6),
(252, 9, 7),
(253, 9, 8),
(254, 9, 9),
(255, 9, 10),
(256, 9, 11),
(257, 9, 12),
(258, 9, 13),
(259, 9, 14),
(260, 9, 15),
(261, 9, 16),
(262, 9, 17),
(263, 9, 18),
(264, 9, 19),
(265, 9, 20),
(266, 9, 21),
(267, 9, 22),
(268, 9, 23),
(269, 9, 24),
(270, 9, 25);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(855) NOT NULL,
  `subject_desc` varchar(855) DEFAULT NULL,
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ind_thm_sbj_qz` (`quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`, `subject_desc`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`, `branch_id`, `quiz_id`) VALUES
(32, 'Math theme1', 'Math theme1', 50006, '2015-05-20 00:21:04', NULL, NULL, 1, 151),
(33, 'Math theme2', 'Math theme2', 50006, '2015-05-20 00:21:10', NULL, NULL, 1, 151),
(34, 'Eng theme1', 'Eng theme1', 50006, '2015-05-20 00:21:18', NULL, NULL, 1, 152),
(35, 'Eng theme2', 'Eng theme2', 50006, '2015-05-20 00:21:27', NULL, NULL, 1, 152);

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t_priority` int(11) NOT NULL,
  `dep_id` int(11) DEFAULT NULL,
  `tech_user_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `t_subject` varchar(800) DEFAULT NULL,
  `t_body` text,
  `tx_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_read`
--

CREATE TABLE IF NOT EXISTS `ticket_read` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE IF NOT EXISTS `ticket_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT NULL,
  `tr_body` text,
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_views`
--

CREATE TABLE IF NOT EXISTS `ticket_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_name` varchar(455) NOT NULL,
  `sql_query` varchar(1255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `treply_files`
--

CREATE TABLE IF NOT EXISTS `treply_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reply_id` int(11) NOT NULL,
  `file_name` varchar(355) DEFAULT NULL,
  `real_file_name` varchar(355) DEFAULT NULL,
  `ticket_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=481 AUTO_INCREMENT=1 ;

INSERT INTO `ticket_views` (`id`, `view_name`, `sql_query`) VALUES
(1, 'My tickets', 'tx.inserted_by = [user_id]'),
(2, 'My pending tickets', 't.tech_user_id = [user_id]'),
(3, 'All tickets', ''),
(4, 'My branch tickets', 'tx.branch_id = [branch_id]'),
(5, 'My closed tickets', 't.status_id=24 and tx.inserted_by = [user_id]'),
(6, 'My "On Hold" tickets', 't.status_id=25 and tx.inserted_by = [user_id]'),
(7, 'My Open tickets', 't.status_id=23 and tx.inserted_by = [user_id]');

-- --------------------------------------------------------

--
-- Table structure for table `tview_role_xreff`
--

CREATE TABLE IF NOT EXISTS `tview_role_xreff` (
  `role_id` int(11) NOT NULL,
  `tview_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tview_role_xreff`
--

INSERT INTO `tview_role_xreff` (`role_id`, `tview_id`) VALUES
(2, 1),
(2, 5),
(2, 6),
(2, 7),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(10, 5),
(10, 6),
(10, 7),
(11, 5),
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(9, 5),
(9, 6),
(9, 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto increment',
  `UserName` varchar(50) NOT NULL COMMENT 'The login of user',
  `Password` varchar(50) NOT NULL COMMENT 'Password of user',
  `Name` varchar(150) NOT NULL COMMENT 'Name of user',
  `Surname` varchar(150) NOT NULL COMMENT 'Surname of user',
  `added_date` datetime NOT NULL COMMENT 'date and time of record created',
  `user_type` int(11) DEFAULT NULL COMMENT 'id from the roles_table',
  `email` varchar(200) DEFAULT NULL COMMENT 'email of user',
  `address` varchar(200) NOT NULL COMMENT 'address of user',
  `phone` varchar(50) NOT NULL COMMENT 'phone number of user',
  `random_str` varchar(100) DEFAULT NULL COMMENT 'random string generated for approving while registering',
  `approved` int(11) NOT NULL COMMENT '1 = approved , 0 = unapproved (for self registration)',
  `disabled` int(11) NOT NULL COMMENT '1 = disabled , 0 = active',
  `user_photo` varchar(500) DEFAULT NULL COMMENT 'the name of profile picture file ',
  `country_id` int(11) DEFAULT NULL COMMENT 'id from countries table',
  `branch_id` int(11) NOT NULL COMMENT 'id from branches table',
  `inserted_by` int(11) DEFAULT NULL COMMENT 'id of user that created the row',
  `inserted_date` datetime DEFAULT NULL COMMENT 'date of creating this row',
  `updated_by` int(11) DEFAULT NULL COMMENT 'id of user that updated the row',
  `updated_date` datetime DEFAULT NULL COMMENT 'date of update',
  `group_id` int(11) NOT NULL COMMENT 'id from user_groups table',
  `self_registered` int(11) NOT NULL DEFAULT '0' COMMENT '1 = self registered , 0 = registered from admin panel',
  `comments` varchar(550) DEFAULT NULL COMMENT 'just comments',
  `system_row` varchar(255) NOT NULL DEFAULT '0' COMMENT '1 = system row , cannot be deleted , 0 = just a row',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `login_unique` (`UserName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50028 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `UserName`, `Password`, `Name`, `Surname`, `added_date`, `user_type`, `email`, `address`, `phone`, `random_str`, `approved`, `disabled`, `user_photo`, `country_id`, `branch_id`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`, `group_id`, `self_registered`, `comments`, `system_row`) VALUES
(50006, 'sysadmin', '48a365b4ce1e322a55ae9017f3daf0c0', 'Polat', 'Alemdar', '0000-00-00 00:00:00', 1, 'elshan999@mail.ru', '', '', NULL, 1, 0, '7e0fa30c1fb3c00fb04e245880896c21.jpg', 241, 1, NULL, NULL, 50006, '2014-03-19 02:15:20', 10, 0, '', '1'),
(50009, 'user5', '0a791842f52a0acfbb3a783378c066b8', 'user5', 'user5', '2013-12-16 07:07:39', 2, 'user5@phpexamscript.net', '', '', NULL, 1, 0, 'ea864abb5fc941f2cc3d2edafb38ed8c.jpg', 63, 1, 50006, '2013-12-16 07:07:39', 50006, '2013-12-16 07:12:56', 1, 0, '', '0'),
(50010, 'user4', '3f02ebe3d7929b091e3d8ccfde2f3bc6', 'user4', 'user4', '2013-12-16 07:10:56', 2, 'user4@phpexamscript.net', '', '', NULL, 1, 0, 'a59f0b20b7edfe34d9424b1daf6a8f17.jpg', 48, 1, 50006, '2013-12-16 07:10:56', 50006, '2013-12-16 07:12:46', 1, 0, '', '0'),
(50011, 'user3', '92877af70a45fd6a2ed7fe81e1236b78', 'user3', 'user3', '2013-12-16 07:11:39', 2, 'user3@phpexamscript.net', '', '', NULL, 1, 0, 'fb85b6aa9d58603a8e48bf5adf1b387d.jpg', 242, 1, 50006, '2013-12-16 07:11:39', 50006, '2013-12-16 07:12:36', 1, 0, '', '0'),
(50012, 'user2', '7e58d63b60197ceb55a1c487989a3720', 'user2', 'user2', '2013-12-16 07:13:24', 2, 'user2@phpexamscript.net', '', '', NULL, 1, 0, '79ae088d04e5ad992a4dcf3d518ad59a.jpg', 241, 1, 50006, '2013-12-16 07:13:24', NULL, NULL, 1, 0, '', '0'),
(50013, 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'user1', 'user1', '2013-12-16 07:17:09', 2, 'user1@phpexamscript.net', '', '', NULL, 1, 0, 'bdadc5f12d14333db5febc24b7bd00ff.jpg', 241, 1, 50006, '2013-12-16 07:17:09', 50006, '2013-12-16 07:17:22', 1, 0, '', '0'),
(50014, 'user6', 'affec3b64cf90492377a8114c86fc093', 'user6', 'user6', '2013-12-16 07:19:47', 2, 'user6@phpexamscript.net', '', '', NULL, 1, 0, 'eb09796b99f8774a473273e5c69f426d.jpg', 240, 1, 50006, '2013-12-16 07:19:47', 50006, '2013-12-16 07:26:51', 1, 0, '', '0'),
(50015, 'user7', '3e0469fb134991f8f75a2760e409c6ed', 'user7', 'user7', '2013-12-16 07:23:10', 2, 'user7@phpexamscript.net', '', '', NULL, 1, 0, 'ca84318b26d4306367712e066a177f61.jpg', 233, 1, 50006, '2013-12-16 07:23:10', 50006, '2013-12-16 07:27:03', 1, 0, '', '0'),
(50016, 'user8', '7668f673d5669995175ef91b5d171945', 'user8', 'user8', '2013-12-16 07:26:13', 2, 'user8@phpexamscript.net', '', '', NULL, 1, 0, '4749c32a2437e1ed0aaf886cd6cc3061.jpg', 213, 1, 50006, '2013-12-16 07:26:13', NULL, NULL, 1, 0, '', '0'),
(50017, 'user9', '8808a13b854c2563da1a5f6cb2130868', 'user9', 'user9', '2013-12-16 07:27:45', 2, 'user9@phpexamscript.net', '', '', NULL, 1, 0, 'b8d7fbff152d4abdf30c0bacd2b707ca.jpg', 190, 1, 50006, '2013-12-16 07:27:45', NULL, NULL, 1, 0, '', '0'),
(50018, 'user10', '990d67a9f94696b1abe2dccf06900322', 'user10', 'user10', '2013-12-16 07:31:52', 2, 'user10@phpexamscript.net', '', '', NULL, 1, 0, '6aefa6350e1342f0414518e4a29a25e3.jpg', 191, 1, 50006, '2013-12-16 07:31:52', 50006, '2013-12-16 07:50:07', 1, 0, '', '0'),
(50022, 'paypal-listener', '+++++++', 'Paypal', 'Listener', '2015-02-18 15:02:09', 1, 'noreply@noreply.com', '', '', NULL, 0, 1, NULL, 241, 1, 50006, '2015-02-18 15:02:09', NULL, NULL, -2, 0, 'user for paypal listener', '-1');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE IF NOT EXISTS `user_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_quiz_id` int(11) DEFAULT NULL COMMENT 'id from user_quizzes table',
  `question_id` int(11) DEFAULT NULL COMMENT 'id from questions table',
  `answer_id` int(11) DEFAULT NULL COMMENT 'id from answers table',
  `user_answer_id` int(11) DEFAULT NULL COMMENT 'id from answers table checked by user',
  `user_answer_text` varchar(3800) DEFAULT NULL COMMENT 'text if question is multi text or free text based',
  `added_date` datetime DEFAULT NULL COMMENT 'date and of row created',
  PRIMARY KEY (`id`),
  KEY `user_answers_ibfk_1` (`user_quiz_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_quiz_id`, `question_id`, `answer_id`, `user_answer_id`, `user_answer_text`, `added_date`) VALUES
(32, 14, 10461, 43570, 43570, NULL, '2015-05-20 00:53:00'),
(33, 15, 10462, 43574, 43574, NULL, '2015-05-20 01:19:59'),
(34, 16, 10462, 43574, 43574, NULL, '2015-05-20 01:20:10'),
(35, 16, 10461, 43570, 43570, NULL, '2015-05-20 01:20:12'),
(36, 17, 10461, 43570, 43570, NULL, '2015-05-20 01:20:22'),
(37, 18, 10462, 43574, 43574, NULL, '2015-05-20 01:20:31'),
(38, 18, 10461, 43570, 43570, NULL, '2015-05-20 01:20:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(555) NOT NULL,
  `group_desc` varchar(555) NOT NULL,
  `is_default` int(11) NOT NULL,
  `inserted_by` int(11) DEFAULT NULL,
  `inserted_date` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `spec_id` int(11) DEFAULT NULL,
  `st_years` int(11) DEFAULT '4',
  `show_in_list` int(11) DEFAULT '1',
  `branch_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_desc`, `is_default`, `inserted_by`, `inserted_date`, `updated_by`, `updated_date`, `start_date`, `spec_id`, `st_years`, `show_in_list`, `branch_id`) VALUES
(1, 'Users', 'users', 0, NULL, '2012-03-12 02:13:31', NULL, NULL, '2012-03-12 02:13:31', NULL, 4, 1, 1),
(3, 'Self registered users', 'Default for new users', 1, NULL, '2012-03-12 02:13:31', NULL, NULL, '2012-03-12 02:13:31', NULL, 4, 1, 1),
(10, 'Admins', 'admins', 0, NULL, '2013-03-12 02:13:31', NULL, NULL, '2012-03-12 00:00:00', NULL, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_logs`
--

CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inserted_by` int(11) NOT NULL,
  `inserted_date` datetime NOT NULL,
  `branch_id` int(11) NOT NULL,
  `log_type` int(11) NOT NULL,
  `log_text` text,
  `ip_address` varchar(50) DEFAULT NULL,
  `headers` varchar(4000) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `log_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=521 ;

--
-- Dumping data for table `user_logs`
--
-- --------------------------------------------------------

--
-- Table structure for table `user_payment_accounts`
--

CREATE TABLE IF NOT EXISTS `user_payment_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pm_id` int(11) NOT NULL,
  `p_account` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `txn_id` (`p_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=3276 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_quizzes`
--

CREATE TABLE IF NOT EXISTS `user_quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) DEFAULT NULL COMMENT 'id of assignment',
  `user_id` int(11) DEFAULT NULL COMMENT 'id of user',
  `status` int(11) DEFAULT NULL COMMENT '1 = started , 2 = finished , 3 = time edned, 4 = manually stopped',
  `added_date` datetime DEFAULT NULL COMMENT 'date of row added',
  `success` int(11) DEFAULT NULL COMMENT '0 = failed , 1= success',
  `finish_date` datetime DEFAULT NULL COMMENT 'date and time of exam finished',
  `pass_score_point` decimal(10,2) DEFAULT NULL COMMENT 'point of user',
  `pass_score_perc` decimal(10,2) DEFAULT NULL COMMENT 'percent of user',
  `archived` int(11) NOT NULL COMMENT '1= archived ,  0 = not archived',
  `level_id` int(11) DEFAULT NULL,
  `uquiz_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignment_id` (`assignment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `user_quizzes`
--

INSERT INTO `user_quizzes` (`id`, `assignment_id`, `user_id`, `status`, `added_date`, `success`, `finish_date`, `pass_score_point`, `pass_score_perc`, `archived`, `level_id`, `uquiz_time`) VALUES
(14, 9, 50013, 2, '2015-05-20 00:48:01', 1, '2015-05-20 00:53:00', '1.00', '50.00', 0, 5, NULL),
(15, 9, 50016, 2, '2015-05-20 01:19:56', 1, '2015-05-20 01:20:00', '2.00', '50.00', 0, 5, NULL),
(16, 9, 50017, 2, '2015-05-20 01:20:08', 1, '2015-05-20 01:20:12', '3.00', '100.00', 0, 5, NULL),
(17, 9, 50018, 2, '2015-05-20 01:20:18', 1, '2015-05-20 01:20:22', '4.00', '50.00', 0, 5, NULL),
(18, 9, 50009, 2, '2015-05-20 01:20:29', 1, '2015-05-20 01:20:32', '5.00', '100.00', 0, 5, NULL),
(19, 10, 50012, 1, '2015-05-20 21:01:38', 0, NULL, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_qst_reviews`
--

CREATE TABLE IF NOT EXISTS `user_quiz_qst_reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_quiz_id` int(11) NOT NULL,
  `qst_id` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `review_type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_quiz_qst_reviews_ibfk_1` (`user_quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_ratings`
--

CREATE TABLE IF NOT EXISTS `user_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate_id` int(11) NOT NULL DEFAULT '0',
  `product_id` varchar(1255) NOT NULL DEFAULT '',
  `point` int(11) NOT NULL DEFAULT '0',
  `ip_address` varchar(255) NOT NULL DEFAULT '',
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_ratings_ibfk_1` (`rate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `variant_quizzes`
--

CREATE TABLE IF NOT EXISTS `variant_quizzes` (
  `id` int(11) DEFAULT NULL,
  `variant_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `asg_id` int(11) NOT NULL,
  KEY `variant_quizzes_ibfk_1` (`asg_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE VIEW `v_imported_users` AS select `imported_users`.`id` AS `UserID`,`imported_users`.`name` AS `Name`,`imported_users`.`surname` AS `Surname`,`imported_users`.`user_name` AS `UserName`,`imported_users`.`password` AS `Password`,`imported_users`.`email` AS `email`,`imported_users`.`branch_id` AS `branch_id`,`imported_users`.`group_id` AS `group_id`,50006 AS `inserted_by`,'nophoto.jpg' AS `user_photo` from `imported_users`;



CREATE  VIEW `v_all_users` AS select `v_imported_users`.`UserID` AS `UserID`,`v_imported_users`.`Name` AS `NAME`,`v_imported_users`.`Surname` AS `Surname`,`v_imported_users`.`email` AS `email`,`v_imported_users`.`branch_id` AS `branch_id`,`v_imported_users`.`group_id` AS `group_id`,concat(`v_imported_users`.`Name`,' ',`v_imported_users`.`Surname`) AS `FullName`,`v_imported_users`.`user_photo` AS `user_photo`,`v_imported_users`.`inserted_by` AS `inserted_by`,0 AS `disabled`,0 AS `approved`,2 AS `user_type`,0 AS `rec_mails`,0 AS `is_technican`,0 AS `system_row`,`v_imported_users`.`UserName` AS `UserName` from `v_imported_users` union select `users`.`UserID` AS `UserID`,`users`.`Name` AS `NAME`,`users`.`Surname` AS `Surname`,`users`.`email` AS `email`,`users`.`branch_id` AS `branch_id`,`users`.`group_id` AS `group_id`,concat(`users`.`Name`,' ',`users`.`Surname`) AS `FullName`,`users`.`user_photo` AS `user_photo`,`users`.`inserted_by` AS `inserted_by`,`users`.`disabled` AS `disabled`,`users`.`approved` AS `approved`,1 AS `user_type`,`r`.`rec_mails` AS `rec_mails`,`r`.`is_technican` AS `is_technican`,`users`.`system_row` AS `system_row`,`users`.`UserName` AS `UserName` from (`users` left join `roles` `r` on((`r`.`id` = `users`.`user_type`))) union select `app_users`.`UserID` AS `UserID`,`app_users`.`Name` AS `NAME`,`app_users`.`Surname` AS `Surname`,`app_users`.`email` AS `email`,`app_users`.`branch_id` AS `branch_id`,`app_users`.`group_id` AS `group_id`,concat(`app_users`.`Name`,' ',`app_users`.`Surname`) AS `FullName`,`app_users`.`user_photo` AS `user_photo`,`app_users`.`inserted_by` AS `inserted_by`,0 AS `disabled`,0 AS `approved`,`app_users`.`app_id` AS `user_type`,`r`.`rec_mails` AS `rec_mails`,`r`.`is_technican` AS `is_technican`,0 AS `system_row`,`app_users`.`UserName` AS `UserName` from (`app_users` left join `roles` `r` on((`r`.`id` = `app_users`.`user_type`)));



CREATE VIEW `v_user_groups` AS select `ug`.`id` AS `id`,`ug`.`group_name` AS `group_name`,`ug`.`group_desc` AS `group_desc`,`ug`.`is_default` AS `is_default`,`ug`.`inserted_by` AS `inserted_by`,`ug`.`inserted_date` AS `inserted_date`,`ug`.`updated_by` AS `updated_by`,`ug`.`updated_date` AS `updated_date`,`ug`.`start_date` AS `start_date`,`ug`.`branch_id` AS `branch_id`,(case when (`ug`.`st_years` <> 0) then (timestampdiff(YEAR,`ug`.`start_date`,now()) + 1) else '-' end) AS `course`,(`ug`.`st_years` - (timestampdiff(YEAR,`ug`.`start_date`,now()) + 1)) AS `years` from `user_groups` `ug` where (((`ug`.`st_years` >= (timestampdiff(YEAR,`ug`.`start_date`,now()) + 1)) or (`ug`.`st_years` = 0)) and (`ug`.`show_in_list` = 1));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `question_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `asg_qbank_quizzes`
--
ALTER TABLE `asg_qbank_quizzes`
  ADD CONSTRAINT `asg_qbank_quizzes_fb1` FOREIGN KEY (`asg_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_diff_level_xreff`
--
ALTER TABLE `assignment_diff_level_xreff`
  ADD CONSTRAINT `assignment_diff_level_xreff_fb1` FOREIGN KEY (`asg_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_qst_views`
--
ALTER TABLE `assignment_qst_views`
  ADD CONSTRAINT `assignment_qst_views_ibfk_1` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_question_points`
--
ALTER TABLE `assignment_question_points`
  ADD CONSTRAINT `assignment_question_points_ibfk_1` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_subject_results`
--
ALTER TABLE `assignment_subject_results`
  ADD CONSTRAINT `assignment_subject_results_ibfk_1` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_usergroup_xreff`
--
ALTER TABLE `assignment_usergroup_xreff`
  ADD CONSTRAINT `assignment_usergroup_xreff_fb1` FOREIGN KEY (`asg_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment_users`
--
ALTER TABLE `assignment_users`
  ADD CONSTRAINT `assignment_users_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `module_reports`
--
ALTER TABLE `module_reports`
  ADD CONSTRAINT `module_reports_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nots`
--
ALTER TABLE `nots`
  ADD CONSTRAINT `nots_ibfk_1` FOREIGN KEY (`asg_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions1_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_groups`
--
ALTER TABLE `question_groups`
  ADD CONSTRAINT `question_groups_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `result_template_contents`
--
ALTER TABLE `result_template_contents`
  ADD CONSTRAINT `FK_result_template_contents_result_templates_id` FOREIGN KEY (`template_id`) REFERENCES `result_templates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles_access_rights`
--
ALTER TABLE `roles_access_rights`
  ADD CONSTRAINT `roles_access_rights_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roles_pages`
--
ALTER TABLE `roles_pages`
  ADD CONSTRAINT `roles_pages_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_reports`
--
ALTER TABLE `role_reports`
  ADD CONSTRAINT `role_reports_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `ind_thm_sbj_qz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_quizzes`
--
ALTER TABLE `user_quizzes`
  ADD CONSTRAINT `user_quizzes_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_quiz_qst_reviews`
--
ALTER TABLE `user_quiz_qst_reviews`
  ADD CONSTRAINT `user_quiz_qst_reviews_ibfk_1` FOREIGN KEY (`user_quiz_id`) REFERENCES `user_quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_ratings`
--
ALTER TABLE `user_ratings`
  ADD CONSTRAINT `user_ratings_ibfk_1` FOREIGN KEY (`rate_id`) REFERENCES `ratings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `variant_quizzes`
--
ALTER TABLE `variant_quizzes`
  ADD CONSTRAINT `variant_quizzes_ibfk_1` FOREIGN KEY (`asg_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE;

