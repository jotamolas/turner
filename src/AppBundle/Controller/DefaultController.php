<?php
namespace AppBundle\Controller;

require '/var/www/html/turner/vendor/mike42/escpos-php/autoload.php';
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\turn;
use AppBundle\Entity\turnLine;
use AppBundle\Entity\turnType;
use AppBundle\Entity\turnState;
use AppBundle\Entity\position;
use AppBundle\Entity\agent;
use AppBundle\Entity\agentState;


class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/turn/create", name="turn_create")
     */
    public function createTurnAction() {
        $turnTypes = $this->getDoctrine()->getRepository(turnType::class)->findAll();
        return $this->render('TicketMachine/turn.selection.html.twig',[
            'types' => $turnTypes
        ]);
    }
    
    /**
     * @Route("/turn/new/{type}", name="turn_new")
     */
    public function newTurnAction(turnType $type) {

        $turn_line = $this->getDoctrine()->getRepository(turnLine::class)->findOneBy([
            "date" => new \DateTime(),
            "type" => $type
        ]);

        if ($turn_line) {

            $em = $this->getDoctrine()->getManager();

            $last_turn = $em->createQuery(
                            'SELECT max(t.id) FROM AppBundle:turn t WHERE t.line = :line'
                    )
                    ->setParameter('line', $turn_line)
                    ->getSingleScalarResult();
           


            if ($last_turn) {

                $last_turn = $this->getDoctrine()->getRepository(turn::class)->find($last_turn);

                $turn = new turn();
                $turn->setLine($turn_line)
                        ->setNumber($last_turn->getNumber() + 1)
                        ->setDate(new \DateTime())
                        ->setTime(new \DateTime())
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'created']))
                /* ->setAgent($this->getDoctrine()->getRepository(\AppBundle\Entity\agent::class)->find(1)) */;
                $em->persist($turn);
                $em->flush();
                
            } else {
                
                $turn = new turn();
                $turn->setLine($turn_line)
                        ->setNumber('1')
                        ->setDate(new \DateTime())
                        ->setTime(new \DateTime())
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'created']))
                /* ->setAgent($this->getDoctrine()->getRepository(\AppBundle\Entity\agent::class)->find(1)) */;
                $em->persist($turn);
                $em->flush();
            }
        } else {
            
            $em = $this->getDoctrine()->getManager();
            
                    
                    
            $agent = $this->getDoctrine()->getRepository(agent::class)->find(1);
            $agent->setState($this->getDoctrine()->getRepository(agentState::class)->find(1));
            $em->flush();

            
            
            $turn_line = new turnLine();
            $turn_line
                    ->setDescription($type->getDescription() . "-" . date('ymd'))
                    ->setDate(new \DateTime())
                    ->setType($type);

            
            $em->persist($turn_line);            
            $em->flush();

            $turn = new turn();
            $turn->setLine($turn_line)
                    ->setDate(new \DateTime())
                    ->setTime(new \DateTime())
                    ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'created']))
                    ->setNumber('1');
            $em->persist($turn);
            $em->flush();
            
        }
        
        /* impresión de turno */
        $this->get('print.service')->printTicket($turn);
        return $this->render('TicketMachine/turn.print.html.twig', [
                    'turn' => $turn
        ]);
    }
    
    
    /**
     * 
     * @Route("/print", name="print")
     *  
     */
    public function printAlgo(){       
        $this->get('print.service')->printAlgo(new turn());
    }
        



    /**
     * @Route("/turn/manage/", name="turn_manage") 
     */
    public function manageTurnAction() {

        $turns = $this->getDoctrine()->getRepository(turn::class)->findBy([
            'date' => new \DateTime(),
                //'state' => $this->getDoctrine()->getRepository(turnState::class)->find(1)
        ]);

        return $this->render('Manager/dashboard.html.twig', [
                    'turns' => $turns,
        ]);
    }

    /**
     * @Route("/turn/assign/{turn}/{agent}", name="turn_assign") 
     */
    public function assignTurnAction(turn $turn, agent $agent) {



        if ($agent->getState()->getDescription() == 'free') {
            if ($turn->getState()->getId() == 1) {

                $turn->setAgent($agent)
                        ->setPosition($this->getDoctrine()->getRepository(position::class)->find(1))
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->find(2))
                ;
                $agent->setState($this->getDoctrine()->getRepository(agentState::class)->find(2));
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash(
                        'info', 'El Turno fue asignado'
                );
            } else {
                $this->addFlash(
                        'warning', 'No puede asignar el Turno desde el estado actual'
                );
            }
        } else {
            $this->addFlash(
                    'warning', 'No puede asignar el Turno cuando su estado es Ocupado, por favor libere el turno tomado'
            );
        }
        return $this->redirectToRoute('turn_manage');
    }

    /**
     * @Route("/turn/end/{turn}", name="turn_end") 
     */
    public function endTurnAction(turn $turn) {
        if ($turn->getState()->getId() == 2) {

            $turn->setState($this->getDoctrine()->getRepository(turnState::class)->find(3))
                 ->getAgent()->setState($this->getDoctrine()->getRepository(agentState::class)->find(1));

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash(
                    'info', 'El turno fue marcado como atendido'
            );
        } else {
            $this->addFlash(
                    'warning', 'No se puede marcar el turno como atendido desde el estado actual'
            );
        }

        return $this->redirectToRoute('turn_manage');
    }
    
    
    
    
    /**
     * @Route("/turn/display"), name="turn_display")
     */
    public function displayTurns(){
        
        $turns = $this->getDoctrine()->getRepository(turn::class)->findToWaitingRoom(new \DateTime());
        
        return $this->render('WaitingRoom/display.html.twig', [
                    'turns' => $turns,
        ]);
    }
}
