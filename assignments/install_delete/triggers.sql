DELIMITER $$
CREATE TRIGGER tg_delete_unused_quizze AFTER DELETE ON variant_quizzes
 FOR EACH ROW DELETE FROM quizzes 
   WHERE 
   quizzes.id=OLD.quiz_id$$
   
CREATE TRIGGER tg_delete_unused_quizze2
AFTER DELETE
ON assignment_users
FOR EACH ROW
  DELETE
    FROM quizzes
  WHERE quizzes.id = OLD.u_quiz_id$$
  
  DELIMITER ;
