<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\turn;
use AppBundle\Entity\turnLine;
use AppBundle\Entity\turnType;
use AppBundle\Entity\turnState;
use AppBundle\Entity\position;
use AppBundle\Entity\agent;
use AppBundle\Entity\agentState;
use AppBundle\Entity\agentSession;

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
        $turnTypes = $this->getDoctrine()->getRepository(turnType::class)->findBy(['isEnabled' => true]);
        //dump($turnTypes);
        return $this->render('TicketMachine/turn.selection.tmpl-b.html.twig', [
                    'types' => $turnTypes
        ]);
    }

    /**
     * @Route("/turn/new/{type}", name="turn_new")
     */
    public function newTurnAction(turnType $type) {

        // Busco si existe una linea ya creada para el dia 
        $turn_line = $this->getDoctrine()->getRepository(turnLine::class)->findOneBy([
            "date" => new \DateTime(),
            "type" => $type
        ]);

        if ($turn_line) {
            //Si exite la linea busco si existe un último turno creado
            $em = $this->getDoctrine()->getManager();
            $last_turn = $em->createQuery(
                            'SELECT max(t.id) FROM AppBundle:turn t WHERE t.line = :line'
                    )
                    ->setParameter('line', $turn_line)
                    ->getSingleScalarResult();


            if ($last_turn) {
                //Busco el ultimo turno de la linea
                $last_turn = $this->getDoctrine()->getRepository(turn::class)->find($last_turn);
                //Creo un nuevo turno y lo persisto sumandole un numero mas alúltimo encontrado
                $turn = new turn();
                $turn->setLine($turn_line)
                        ->setNumber($last_turn->getNumber() + 1)
                        ->setDate(new \DateTime())
                        ->setTime(new \DateTime())
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'created']));
                $em->persist($turn);
                $em->flush();
            } else {
                //Creo el primer turno de la linea--- este else es al pedo poruqe si la linea esta creada si o si tiene un turno
                $turn = new turn();
                $turn->setLine($turn_line)
                        ->setNumber('1')
                        ->setDate(new \DateTime())
                        ->setTime(new \DateTime())
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'created']));
                $em->persist($turn);
                $em->flush();
            }
        } else {

            //Si no existe una linea creo una nueva
            $em = $this->getDoctrine()->getManager();
            $turn_line = new turnLine();
            $turn_line
                    ->setDescription($type->getDescription() . "-" . date('ymd'))
                    ->setDate(new \DateTime())
                    ->setType($type);
            $em->persist($turn_line);
            $em->flush();
            //Creo el primer turno de la linea.
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
        $result = $this->get('print.service')->printTicket($turn);
        //dump($result);
        return $this->render('TicketMachine/turn.print.html.twig', [
                    'turn' => $turn,
                    'print_result' => $result
        ]);
    }

    /**
     * 
     * @Route("/turn/manage/position/select/{agent}", name="turn_manage_position_selection_noposition")
     * @Route("/turn/manage/position/select/{agent}/{position}", name="turn_manage_position_selection")
     * 
     * 
     */
    public function selectPositionAction(agent $agent, position $position = null) {


        if ($agent->getActiveSession()) {
            $response = $this->generateUrl('turn_manage', [
                'agent' => $agent->getId(),
                'session' => $agent->getActiveSession()->getId()
            ]);

            return $this->redirect($response);
        } else {
            if ($position) {

                /* llamo al servicio encargado de crear la sesion */
                $session = $this->get('session.service')->open($position, $this->getUser());
                $response = $this->generateUrl('turn_manage', [
                    'agent' => $agent->getId(),
                    'session' => $session->getId()
                ]);

                return $this->redirect($response);
            } else {

                $postions = $this->getDoctrine()->getRepository(position::class)->findAll();

                return $this->render('Position/position.selection.html.twig', [
                            'positions' => $postions,
                            'agent' => $agent
                ]);
            }
        }
    }

    /**
     * @Route("/turn/manage/{agent}", name="turn_manage") 
     */
    public function manageTurnAction(agent $agent) {

        if ($agent->getActiveSession()) {

            $turns = $this->getDoctrine()->getRepository(turn::class)->findToManageTurn(new \DateTime, 10);

            //dump($agent->getActiveSession());

            return $this->render('Manager/dashboard.html.twig', [
                        'turns' => $turns,
                        'session' => $agent->getActiveSession(),
            ]);
        } else {

            $response = $this->generateUrl('turn_manage_position_selection_noposition', [
                'agent' => $agent->getId()
            ]);
            return $this->redirect($response);
        }
    }

    /**
     * @Route("/turn/manage/assign/{turn}/{agent}", name="turn_manage_assign") 
     */
    public function assignTurnAction(turn $turn, agent $agent) {

        if ($agent->getState()->getDescription() == 'idle') {

            if ($turn->getState()->getDescription() == 'calling') {

                $turn->setAgent($agent)
                        ->setPosition($agent->getActiveSession()->getPosition())
                        ->setSession($agent->getActiveSession())
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'assigned']))
                ;
                $agent->setState($this->getDoctrine()->getRepository(agentState::class)->findOneBy(['description' => 'busy']))
                        ->getActiveSession()->getPosition()->setActiveAgent($agent)
                        ->setActiveTurn($turn);

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

            if ($turn->getState()->getDescription() == 'calling' && $turn->getAgent() == $agent) {
                $turn->setAgent($agent)
                        ->setPosition($agent->getActiveSession()->getPosition())
                        ->setSession($agent->getActiveSession())
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'assigned']))
                ;
                $agent->setState($this->getDoctrine()->getRepository(agentState::class)->findOneBy(['description' => 'busy']))
                        ->getActiveSession()->getPosition()->setActiveAgent($agent)
                        ->setActiveTurn($turn);
                ;

                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash(
                        'info', 'El Turno fue asignado'
                );
            } else {

                $this->addFlash(
                        'warning', 'No puede tomar el Turno cuando su estado es Ocupado o cuando usted No llamo al Turno'
                );
            }
        }

        return $this->redirectToRoute('turn_manage', [
                    'agent' => $agent->getId()
        ]);
    }

    /**
     * @Route("/turn/manage/end/{turn}", name="turn_manage_end") 
     */
    public function endTurnAction(turn $turn) {

        if ($turn->getState()->getId() == 2) {

            if ($turn->getAgent() == $this->getUser()) {

                $turn->setState($this->getDoctrine()->getRepository(turnState::class)->find(3))
                        ->getAgent()->setState($this->getDoctrine()->getRepository(agentState::class)->find(1))
                        ;
                $turn->getPosition()->setActiveAgent(NULL)->setActiveTurn(NULL);

                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash(
                        'info', 'El turno fue marcado como atendido'
                );
            } else {
                $this->addFlash(
                        'warning', 'El usuario ' . $turn->getAgent()->getUsername() . ' tomo el turno. No esta autorizado a finalizarlo'
                );
            }
        } else {
            $this->addFlash(
                    'warning', 'No se puede marcar el turno como atendido desde el estado actual'
            );
        }

        return $this->redirectToRoute('turn_manage', [
                    'agent' => $turn->getAgent()->getId(),
        ]);
    }

    /**
     * @Route("/turn/manage/call/{turn}/{agent}", name="turn_manage_call")
     */
    public function callTurnAction(turn $turn, agent $agent) {

        if ($agent->getState()->getDescription() == 'idle') {
            if ($turn->getState()->getDescription() == 'created') {

                $turn
                        ->setAgent($agent)
                        ->setPosition($agent->getActiveSession()->getPosition())
                        ->setSession($agent->getActiveSession())
                        ->setState($this->getDoctrine()->getRepository(turnState::class)->findOneBy(['description' => 'calling']))
                ;
                $agent->setState($this->getDoctrine()->getRepository(agentState::class)->findOneBy(['description' => 'busy']))
                        ->getActiveSession()->getPosition()->setActiveAgent($agent)
                        ->setActiveTurn($turn);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash(
                        'info', 'Llamando al Turno' . $turn->getLabel()
                );
            } else {
                $this->addFlash(
                        'warning', 'No se puede llamar al Turno desde el estado actual'
                );
            }
        } else {
            $this->addFlash(
                    'warning', 'No puede llamar a un Turno cuando su estado es Ocupado, por favor libere el turno que ya ha tomado'
            );
        }

        return $this->redirectToRoute('turn_manage', [
                    'agent' => $agent->getId()
        ]);
    }

    /**
     * @Route("/turn/display"), name="turn_display")
     */
    public function displayTurns() {

        $turns = $this->getDoctrine()->getRepository(turn::class)->findToWaitingRoom(new \DateTime());
        
        $positions = $this->getDoctrine()->getRepository(position::class)->findAll();
        //dump($positions);
        //dump($turns);
        return $this->render('WaitingRoom/display-tmpl-b.html.twig', [
                    'turns' => $turns,
                    'positions' => $positions
        ]);
    }
    
    
    /**
     * @Route("/turn/test"), name="turn_display")
     */
    public function testSessions(){
        $opened_session = $this->getDoctrine()->getRepository(\AppBundle\Entity\agentSession::class)->findBy(['isOpen' => true]);
        /*foreach ($opened_session as $s){
            $this->get('session.service')->close($s);
        }*/
        
        dump($opened_session);
    }

}
