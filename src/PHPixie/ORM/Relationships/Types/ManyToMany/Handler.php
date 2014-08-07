<?php

namespace PHPixie\ORM\Relationships\Types\ManyToMany;

class Handler extends \PHPixie\ORM\Relationships\Relationship\Handler
{
    public function query($side, $related)
    {
        $config = $side->config();
        $side = $side->type();
        $model = $config->get($side.'Model');
        $property = $config->get($side.'Property');
        $repository = $this->repositories->get($model);
        return $repository->query()->related($property, $related);
    }

    public function loadProperty($side, $model)
    {
        
    }

    public function linkPlan($config, $leftItems, $rightItems)
    {
        $plan = $this->plans->plan();
        list($leftSide, $rightSide) = $this->plannerSides($config, $leftItems, $rightItems);
        $pivot = $this->plannerPivot($config);
        $this->planners->pivot()->link($pivot, $leftSide, $rightSide, $plan);

        return $plan;
    }

    public function unlinkPlan($config, $leftItems = null, $rightItems = null)
    {
        $plan = $this->plans->plan();
        list($leftSide, $rightSide) = $this->plannerSides($config, $leftItems, $rightItems);
        $pivot = $this->plannerPivot($config);
        $this->planners->pivot()->unlink($pivot, $leftSide, $rightSide, $plan);

        return $plan;
    }
    
    public function unlinkAllPlan($side, $items)
    {
        $config = $side->config();
        $plan = $this->plans->plan();
        $plannerSide = $this->plannerSide($config, $side->type(), $items);
        $pivot = $this->plannerPivot($config);
        $this->planners->pivot()->unlinkAll($pivot, $plannerSide, $plan);

        return $plan;
    }

    protected function plannerSides($config, $leftItems, $rightItems)
    {
        $sides = array();
        $pivotPlanner = $this->planners->pivot();

        foreach (array('left', 'right') as $side) {
            $model = $config->get($side.'Model');
            $items = $side === 'left' ? $leftItems : $rightItems;

            if ($items === null) {
                $sides[] = null;
            } else {
                $sides[] = $pivotPlanner->side(
                                            $items,
                                            $this->repositories->get($model),
                                            $config->get($side.'PivotKey')
                                        );
            }
        }

        return $sides;
    }

    protected function pivotConnection($config)
    {
        if ($config->pivotConnection !== null)
            return $this->ormBuilder->databaseConnection($config->pivotConnection);

        return $this->repositories->get($config->leftModel)->connection();
    }

    protected function plannerPivot($config)
    {
        $pivotConnection = $this->pivotConnection($config);

        return $this->planners->pivot()->pivot($pivotConnection, $config->pivot);
    }

    public function mapQuery($side, $group, $query, $plan)
    {
        $dependencies   = $this->getMappingDependencies($side);
        $config         = $dependencies['config'];
        $sideRepository = $dependencies['sideRepository'];
        $inPlanner      = $this->planners->in();

        $sideIdField = $sideRepository->idField();

        $sideQuery = $sideRepository->databaseSelectQuery()->fields(array($sideIdField));
        $this->groupMapper->mapConditions(
                                            $sideQuery,
                                            $group->conditions(),
                                            $sideRepository->modelName(),
                                            $plan
                                        );


        $pivotQuery = $dependencies['pivot']->databaseSelectQuery();
        $inPlanner->subquery(
                            $pivotQuery,
                            $config->get($dependencies['type'].'PivotKey'),
                            $sideQuery,
                            $sideIdField,
                            $plan
                        );

        $inPlanner->subquery(
                            $query,
                            $dependencies['opposingRepository']->idField(),
                            $pivotQuery,
                            $config->get($dependencies['opposing'].'PivotKey'),
                            $plan,
                            $group->logic(),
                            $group->negated()
                        );
    }

    public function mapPreload($side, $resultStepLoader, $preloadPlan)
    {
        
        $dependencies   = $this->getMappingDependencies($side);
        $config         = $dependencies['config'];
        $sideRepository = $dependencies['sideRepository'];
        $inPlanner      = $this->planners->in();

        $pivotQuery = $dependencies['pivot']->databaseSelectQuery();
        
        $inPlanner->result(
                            $pivotQuery,
                            $config->get($dependencies['opposing'].'PivotKey'),
                            $resultStepLoader->resultStep(),
                            $dependencies['opposingRepository']->idField(),
                            $preloadPlan
                        );
        
        $pivotStep = $this->steps->reusableResult($pivotQuery);
        $preloadPlan->add($pivotStep);
    
        $sideQuery = $sideRepository->databaseSelectQuery();

        $inPlanner->result(
                            $sideQuery,
                            $sideRepository->idField(),
                            $pivotStep,
                            $config->get($dependencies['type'].'PivotKey'),
                            $preloadPlan
                        );
        
        $preloadStep = $this->steps->reusableResult($sideQuery);
        $preloadPlan->add($preloadStep);
        $loader = $this->loaders->reusableResult($sideRepository, $preloadStep);
        return $this->relationship->preloader($side, $loader, $pivotStep);
        
    }

    protected function getMappingDependencies($side)
    {
        $dependencies = array();

        $type     = $side->type();
        $config   = $side->config();
        $opposing = $type === 'left' ? 'right' : 'left';

        return array(
            'config'             => $config,
            'type'               => $type,
            'opposing'           => $opposing,
            'pivot'              => $this->plannerPivot($config),
            'sideRepository'     => $this->repositories->get($config->get($type.'Model')),
            'opposingRepository' => $this->repositories->get($config->get($opposing.'Model'))
        );

        return $dependencies;
    }

    public function linkProperties($config, $left, $right)
    {
        $this->processProperties('add', $left, $config->leftProperty, $right);
        $this->processProperties('add', $right, $config->rightProperty, $left);
    }

    public function unlinkProperties($config, $left, $right)
    {
        $this->processProperties('remove', $left, $config->leftPropert, $right);
        $this->processProperties('remove', $right, $config->rightProperty, $left);
    }

    public function unlinkAllProperties($side, $owners)
    {
        $this->processProperties('removeAll', $owners , $side->propertyName(), array());
    }
    
    public function resetProperties($side, $items)
    {
        $this->processProperties('reset', $items, $side->propertyName(), array());
    }

    protected function processProperties($action, $owners, $ownerProperty, $items)
    {
        if (!is_array($owners))
            $owners = array($owners);

        if (!is_array($items))
            $items = array($items);
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        if (!is_array($owners))
            $owners = array($owners);

        if (!is_array($items))
            $items = array($items);

        if ($action === 'reset') {
            $resetOwners = true;
        } else {
            $resetOwners = false;
            foreach ($items as $item) {
                if (!($item instanceof \PHPixie\ORM\Model)) {
                    $resetOwners = true;
                    break;
                }
            }
        }
        
        foreach ($owners as $owner) {
            if (!($owner instanceof \PHPixie\ORM\Model))
                continue;

            $property = $owner->relationshipProperty($ownerProperty);
            if ($property === null || !$property->isLoaded())
                continue;

            if ($resetOwners) {
                $property->reset();
                continue;
            }

            $loader = $property->value();
            if ($action === 'remove') {
                $loader->remove($items);
            if ($action === 'removeAll') {
                $loader->removeAll();
            } else {
                $loader->add($items);
            }
        }
    }

    public function handleDeletion($modelName, $side, $resultStep, $plan)
    {
        $config = $side->config();
        $query = $this->pivotConnection($config)->query('delete');
        $this->planners->query()->setSource($query, $config->pivot);
        $pivotKey = $config->get($side-> type().'PivotKey');
        $repository = $this->repositories->get($modelName);
        $this->planners->in()->result($query, $pivotKey, $resultStep, $repository->idField());
        $deleteStep = $this->steps->query($query);
        $plan->push($deleteStep);
    }
}
