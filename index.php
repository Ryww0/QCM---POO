<?php

class Qcm
{
    private $questions = [];

    public function addQuestion($question)
    {
        $this->questions[] = $question;
        return $this;
    }

    public function generate()
    {
        if (isset($_POST['id'])) {
            foreach ($this->questions as $key => $question) {
                echo $question->getQuestion() . '<br><br>';
                if ($_POST['q' . $key] == $question->getNumCorrect()) {
                    echo 'Bonne réponse !' . '<br>';
                    echo $question->getExplains() . '<br><br><br>';
                } else {
                    echo 'Mauvaise réponse !' . '<br>';
                    echo $question->getExplains() . '<br><br><br>';
                }
            }
        } else {
            echo '<form method="post">';
            foreach ($this->questions as $i => $question) {
                echo $question->getQuestion();
                echo '<br><br>';
                foreach ($question->getAnswers() as $j => $answer) {
                    echo '<input type="hidden" name="id">';
                    echo '<input type="radio" name="q' . $i . '" value="' . $j . '"><label for="' . $j . '">' . $answer->getAnswer() . '</label>';
                    echo '<br><br>';
                }
            }
            echo '<input type="submit" value="Valider"></form>';
            echo '<br><br>';
        }
    }
}

class Question
{
    private string $question;
    private string $explains;
    private array $answers = [];

    public function __construct(string $question)
    {
        $this->question = $question;
    }

    public function addAnswer($answer)
    {
        $this->answers[] = $answer;
    }

    public function setExplains(string $explains)
    {
        $this->explains = $explains;
    }

    public function getExplains(): string
    {
        return $this->explains;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getNumCorrect()
    {
        foreach ($this->answers as $i => $answer) {
            if ($answer->getStatus()) {
                return $i;
            }
        }
    }
}

class Answer
{
    private string $answer;
    private $statut;
    const BONNE_REPONSE = true;
    const WRONG = false;


    public function __construct(string $answer, $statut = Answer::WRONG)
    {
        $this->answer = $answer;
        $this->statut = $statut;
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function getStatus()
    {
        return $this->statut;
    }
}

var_dump($_POST);
$qcm = new Qcm();

$question1 = new Question('Et paf, ça fait ...');
$question1->addAnswer(new Answer('Des mielpops'));
$question1->addAnswer(new Answer('Des chocapics', Answer::BONNE_REPONSE));
$question1->addAnswer(new Answer('Des actimels'));
$question1->setExplains('Et oui, la célèbre citation est « Et paf, ça fait des chocapics ! »');
$qcm->addQuestion($question1);

$question2 = new Question('Qu\'elle est la couleur du cheval blanc d\'Henry IV');
$question2->addAnswer(new Answer('blanc', Answer::BONNE_REPONSE));
$question2->addAnswer(new Answer('rouge'));
$question2->addAnswer(new Answer('bleu'));
$question2->setExplains('C\'est écrit dans la question du con');
$qcm->addQuestion($question2);

$qcm->generate();
